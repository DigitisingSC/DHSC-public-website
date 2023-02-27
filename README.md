# DHSC public website


Drupal distribution for the Digitising Health and Social Care website. Based on the LocalGov Drupal distiribution https://github.com/localgovdrupal/localgov.


## Built With



* CMS: [Drupal](http://www.drupal.org), through [recommended-project](https://github.com/drupal/recommended-project)


To install locally, you will need Composer and we recommend using DDEV for a consistent developer environment.


* Development Environment: [ddev](https://ddev.readthedocs.io/en/stable/).
* Storybook
* CI/CD: [GitHub Actions](https://github.com/features/actions)


## Getting Started



We use GNU Make utility to help setting up, maintaining and running common tasks on local instances.

Makefile "targets" - jargon for commands - must be run from the project root directory, where the

Makefile file lives.


See the "Additional Notes - Make utilities" section below for the whole list of commands.


### Prerequisites


Before starting setting up your local instance, you need:



* [Docker and docker-compose](https://www.docker.com/community-edition).

* [Ddev](https://ddev.readthedocs.io/en/stable/#installation)

* PHP >= 8.1

* [Composer](https://getcomposer.org/download/)

* PHP Code Sniffer and Drupal Coding Standards (see **Coding style tests** section)

* GNU Make utility (Install `build-essential` on Linux, 'Developer tools' or 'Xcode' on Mac or via `homebrew`)





## Installing DHSC locally with composer



Change directory into the DHSC-public-website directory and run ddev start.



```

cd DHSC-public-website

make start

```



Once ddev has finished building, use ddev to run composer install and the site installer.



```

make install

make si

```



Note: As you might be running a different version of PHP on your host machine from the version that DDEV runs, it is advisable to run composer install from within DDEV. This ensures dependencies reflect the PHP version that the webserver is actually running.


## Storybook
The Storybook implementation of this project is purely for documentation purposes. Styles have been inherited from the main `localgov_base` theme and loaded in `docroot/themes/custom/dhsc_theme/.storybook/preview.js`. The `dhsc_theme` must remain the child of `localgov_base`, and inherit component structure and templates from it.

Storybook has been implemented in node version 16.
### Installing Storybook

In order to install the Storybook node dependencies run `make storybook-install`.

### Starting Storybook
You can start the Storybook by running the command:

`make storybook`

This should spin up the Storybook in 'watch mode' and launch it in your default browser.

### Make changes to the Storybook

Once the Storybook is up and running, any changes to the markup will trigger Storybook to rebuild.

## Working with Solr

The website is using Solr as a search server. On dev, staging and production we connect to Acquia.

Local solr for development is accessible at `http://${project-name}.ddev.site:8983/solr/#/`.

## Make utilities



*  `make install`: sets up your instance.

*  `make up`: starts the instance. From second time on run this command to start all the services.

*  `make drupal-site-install`: sometime can be useful to destroy and re-create the drupal installation.

*  `make cex`: exports Drupal configuration

*  `make cim`: imports Drupal configuration

*  `make cc`: cleans Drupal cache

*  `make uli`: runs drush uli

*  `make export-db`: creates a DB dump/export in project root directory (will be ignored from Git)

*  `make storybook-install`: installs the Storybook dependencies

*  `make storybook`: starts Storybook in watch mode


## Authors



*  **Matt Lambert** - *Initial work* - [matthew.lambert@tpximpact.com](mailto:matthew.lambert@tpximpact.com)

*  **Dominic Krone** - *Initial work* - [dominic.krone@tpximpact.com](mailto:dominic.krone@tpximpact.com)

*  **Endre Soo** - *Initial work* - [endre.soo@tpximpact.com](mailto:endre.soo@tpximpact.com)
