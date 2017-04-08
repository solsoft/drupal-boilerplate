# Composer
Composer is used to list PHP dependencies as well as external assets.  
A few packages are used to expand composer's functionality:
- [cweagans/composer-patches](https://github.com/cweagans/composer-patches): applies custom patches to dependencies
- [wikimedia/composer-merge-plugin](https://github.com/wikimedia/composer-merge-plugin): merges additional composer.json files
- [composer/installers](https://github.com/composer/installers): supports additional types of packages, ie, Drupal
- [derhasi/composer-preserve-paths](https://github.com/derhasi/composer-preserve-paths): to force paths and files to be preserved
- [davidbarratt/drupal-structure](https://github.com/davidbarratt/drupal-structure), to make the directory structure of Drupal 6/7 look like Drupal 8

## Dependencies
The dependences and managed and organized into different directories:
- web: Drupal core, profiles, modules and themes
- assets: External JavaScript, CSS and other kinds of assets (Drupal Libraries)
- vendor: Composer packages and remaining dependencies

## Configuration
Composer will look for composer.json configuration files in a variety of places.  

### Standard
The main file is loaded with the minimum set of configuration and dependencies:
- composer.json

Custom configuration and dependencies may be loaded from:
- composer.local.json

Patches are set to be loaded from:
- composer.patches.json

### Composer directory
The `composer/` directory contains files to add additional dependencies as 
Drupal Contributed modules and themes, as well as external assets/libraries:
- composer/composer.json
- composer/*/composer.json
- composer/*/*/composer.json
- composer/*/*/*/composer.json

### Drupal Custom directories
Additional files can be included for custom Drupal profiles, modules and themes:
- web/profiles/custom/*/composer.json
- web/profiles/custom/*/*/composer.json
- web/profiles/custom/*/*/*/composer.json
- web/modules/custom/*/composer.json
- web/modules/custom/*/*/composer.json
- web/modules/custom/*/*/*/composer.json
- web/themes/custom/*/composer.json
- web/themes/custom/*/*/composer.json
- web/themes/custom/*/*/*/composer.json

### Drupal Core
The Core configuration is loaded to provide dependencies for development:
- web/core/composer.json

