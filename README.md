# DHSC-public-website

Drupal distribution for the Digitising Health and Social Care website. Based on the LocalGov Drupal distiribution https://github.com/localgovdrupal/localgov.

## Composer and DDEV

To install locally, you will need Composer and we recommend using DDEV for a consistent developer environment.

 - https://getcomposer.org/
 - https://ddev.com/

## Installing DHSC locally with composer

Change directory into the DHSC-public-website directory and run ddev start.

```
cd DHSC-public-website
ddev start
```

Once ddev has finished building, use ddev to run composer install and the site installer.

```
ddev composer install
ddev drush si localgov -y
```

Note: As you might be running a different version of PHP on your host machine from the version that DDEV runs, it is advisable to run composer install from within DDEV. This ensures dependencies reflect the PHP version that the webserver is actually running.
