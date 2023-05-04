REMOTE_ALIAS = @dhsc
THEME_DIR = docroot/themes/custom/dhsc_theme

.SILENT:

install: up composer-install cim restart uli

uninstall: rm

up:
	ddev start

down:
	ddev stop

restart:
	ddev restart

rm: down
	ddev remove

cc:
	ddev drush cr

cex:
	ddev drush cex -y

cim:
	ddev drush cim -y

uli:
	ddev drush uli

si:
	ddev drush si localgov -y

composer-install:
	ddev composer install
	ddev composer run-script drupal-scaffold

mysql:
	ddev exec mysql -u db -pdb db

export-db:
	ddev export-db --file=dec-$$(date +%Y%m%d--%H%M).sql.gz

coding-standards:
	docker run --rm -v `pwd`:/work skilldlabs/docker-phpcs-drupal phpcs --standard=Drupal,DrupalPractice docroot/themes/custom/ docroot/modules/custom/ --ignore="*/node_modules/*, themes/custom/dhsc_theme/stories, themes/custom/dhsc_theme/.storybook"

fe-watch:
	npm run watch --prefix ${THEME_DIR}

fe-build:
	npm run build --prefix ${THEME_DIR}

fe-install:
	npm i --prefix ${THEME_DIR}

storybook:
	npm run storybook --prefix ${THEME_DIR}
