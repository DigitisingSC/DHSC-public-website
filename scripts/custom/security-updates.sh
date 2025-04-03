#!/bin/bash

# Check for security updates using composer audit
SECURITY_UPDATES=$(composer audit --locked --format=json | jq -r '.advisories | keys[] | select(startswith("drupal/"))')

# Exit if no security updates are found
if [ -z "$SECURITY_UPDATES" ]; then
  echo "No security updates available for Drupal core or contrib modules."
  exit 0
fi

echo "Security updates available for:"
echo "$SECURITY_UPDATES"

BRANCH="feature/security-updates-$(date +%m-%Y)"

# ---- 1. Check if PR branch already exists ----

BRANCH_EXISTS=$(git ls-remote --heads origin "$BRANCH")

git fetch origin --prune

# Check if the branch exists remotely
if git ls-remote --exit-code --heads origin "$BRANCH"; then
  echo "Branch $BRANCH already exists. Checking it out and resetting to remote."

  # Fetch the specific branch to ensure we have a local reference
  git fetch origin "$BRANCH":"$BRANCH"

  # Check if the branch exists locally
  if git rev-parse --verify "$BRANCH"; then
    git checkout "$BRANCH"
  else
    git checkout -b "$BRANCH" origin/"$BRANCH"
  fi

  # Ensure local branch matches remote (avoiding divergence issues)
  git reset --hard "$BRANCH"
else
  echo "Branch $BRANCH does not exist. Creating a new one."
  git checkout -b "$BRANCH"
fi
# ---- 2. Update Drupal core ----

# Detect if drupal/core-recommended is installed
if composer show drupal/core-recommended --no-interaction --quiet; then
  echo "Using drupal/core-recommended. Updating with all dependencies."
  CORE_UPDATE_COMMAND='composer update "drupal/core-*" --with-all-dependencies --ignore-platform-reqs'
else
  echo "Using drupal/core. Updating with dependencies."
  CORE_UPDATE_COMMAND='composer update drupal/core --with-dependencies --ignore-platform-reqs'
fi

# Check if core is in the security updates list and update it
if echo "$SECURITY_UPDATES" | grep -q "drupal/core"; then
  echo "Updating Drupal core."
  eval "$CORE_UPDATE_COMMAND"
fi

# ---- 3. Update contrib modules ----

CONTRIB_UPDATES=$(echo "$SECURITY_UPDATES" | grep -v "drupal/core")

if [ -n "$CONTRIB_UPDATES" ]; then
  echo "Updating contrib modules."

  for MODULE in $CONTRIB_UPDATES; do
    echo "Updating $MODULE."

    # Get the pinned version (if any)
    PINNED_VERSION=$(jq -r --arg MODULE "$MODULE" '.require[$MODULE] // empty' composer.json)

    if [[ -n "$PINNED_VERSION" && "$PINNED_VERSION" != ^* && "$PINNED_VERSION" != ~* ]]; then
      echo "$MODULE is pinned to $PINNED_VERSION"

      # Find the latest available version within the constraint
      LATEST_VERSION=$(composer show "$MODULE" --all --format=json | jq -r --arg PINNED "$PINNED_VERSION" '
        .versions[] |
        select(startswith($PINNED | split(".")[0])) |
        select(test("^[0-9]+(\\.[0-9]+)*$"))' | sort -V | tail -n 1)

      if [ -n "$LATEST_VERSION" ]; then
        echo "Updating $MODULE to $LATEST_VERSION"
        composer require "$MODULE:$LATEST_VERSION" --no-update --ignore-platform-reqs
        composer update "$MODULE" --with-dependencies --ignore-platform-reqs
      else
        echo "No security update available for pinned module $MODULE"
      fi
    else
      echo "Updating $MODULE normally."
      composer update "$MODULE" --with-dependencies --ignore-platform-reqs
    fi
  done
fi

# ---- 4. Commit and push changes ----

if ! git diff --quiet; then
  git add composer.json composer.lock
  git commit -m "Security updates for Drupal core and contrib modules"
  git push origin "$BRANCH"

  # ---- 5. Create a pull request on Bitbucket ----

  EXISTING_PR=$(curl -s -u "$BITBUCKET_USER:$BITBUCKET_APP_PASSWORD" \
    "https://api.bitbucket.org/2.0/repositories/$BITBUCKET_WORKSPACE/$BITBUCKET_REPO_SLUG/pullrequests?state=OPEN" | jq -r --arg BRANCH "$BRANCH" '.values[] | select(.source.branch.name == $BRANCH) | .links.html.href')

  if [[ -n "$EXISTING_PR" && "$EXISTING_PR" != "null" ]]; then
    PR_STATUS="Pull request already exists: $EXISTING_PR. Branch updated."
    PR_URL="$EXISTING_PR"

  else
    # Create a new pull request if none exists
    PR_RESPONSE=$(curl -s -X POST -u "$BITBUCKET_USER:$BITBUCKET_APP_PASSWORD" \
      -H "Content-Type: application/json" \
      "https://api.bitbucket.org/2.0/repositories/$BITBUCKET_WORKSPACE/$BITBUCKET_REPO_SLUG/pullrequests" \
      -d "{
            \"title\": \"Security updates for Drupal - $(date +%m-%Y)\",
            \"source\": { \"branch\": { \"name\": \"$BRANCH\" } },
            \"destination\": { \"branch\": { \"name\": \"main\" } },
            \"close_source_branch\": true
          }")

    PR_STATUS="Pull request created: $PR_URL"
    PR_URL=$(echo "$PR_RESPONSE" | jq -r '.links.html.href')
  fi

  echo "$PR_STATUS"

  # ---- 6. Send Slack Notification ----

  if [ -n "$SLACK_WEBHOOK_URL" ]; then
    curl -X POST -H 'Content-type: application/json' --data "{
      \"blocks\": [
        {
          \"type\": \"section\",
          \"text\": { \"type\": \"mrkdwn\", \"text\": \"*Project*: $BITBUCKET_REPO_FULL_NAME\" }
        },
        {
          \"type\": \"section\",
          \"text\": { \"type\": \"mrkdwn\", \"text\": \"*$PR_STATUS*: <$PR_URL|View PR>\" }
        }
      ]
    }" "$SLACK_WEBHOOK_URL"
  fi

else
  echo "No changes were detected."
fi
