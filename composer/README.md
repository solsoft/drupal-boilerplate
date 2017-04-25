# Composer
Composer is used to list PHP dependencies as well as external assets.  
A few packages are used to expand composer's functionality:
- [cweagans/composer-patches](https://github.com/cweagans/composer-patches): applies custom patches to dependencies
- [wikimedia/composer-merge-plugin](https://github.com/wikimedia/composer-merge-plugin): merges additional composer.json files
- [composer/installers](https://github.com/composer/installers): supports additional types of packages and their custom paths, ie, Drupal
- [oomphinc/composer-installers-extender](https://github.com/oomphinc/composer-installers-extender): allows custom paths for any type of package
- [mnsami/composer-custom-directory-installer](https://github.com/mnsami/composer-custom-directory-installer): allows custom paths for any type of package
- [derhasi/composer-preserve-paths](https://github.com/derhasi/composer-preserve-paths): to force paths and files to be preserved
- [drupal-composer/drupal-scaffold](https://github.com/drupal-composer/drupal-scaffold): to add missing files from Drupal 8 Core

## Dependencies
The dependences and managed and organized into different directories:
- app: Drupal core, profiles, modules and themes
- assets: External JavaScript, CSS and other kinds of assets (Drupal Libraries)
- vendor: Composer packages and remaining dependencies

## Configuration
Composer will look for composer.json configuration files in a variety of places.  

### Standard
The main file is loaded with the minimum set of configuration and dependencies:
- composer.json

### Composer.examples directory
The `composer/` directory contains files that add additional dependencies as
Drupal Contributed modules and themes, as well as external assets/libraries.  
These are not by default read by composer and must be copied to the `composer`
directory for inclusion in the project deployment.

### Composer directory
The `composer/` directory may contain files to add additional dependencies.  
Composer will look for files under these paths:
- composer/composer.json
- composer/*/composer.json
- composer/*/*/composer.json
- composer/*/*/*/composer.json

Custom configuration and dependencies may be loaded from:
- composer/composer.local.json

### Drupal Custom directories
Additional files can be included for custom Drupal profiles, modules and themes:
- app/profiles/custom/*/composer.json
- app/profiles/custom/*/*/composer.json
- app/profiles/custom/*/*/*/composer.json
- app/modules/custom/*/composer.json
- app/modules/custom/*/*/composer.json
- app/modules/custom/*/*/*/composer.json
- app/themes/custom/*/composer.json
- app/themes/custom/*/*/composer.json
- app/themes/custom/*/*/*/composer.json

### Drupal Core
The Core configuration is loaded to provide dependencies for development:
- app/core/composer.json
