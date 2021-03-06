# drupal-boilerplate
[![Build Status](https://travis-ci.org/solsoft/drupal-boilerplate.svg?branch=8.x)](https://travis-ci.org/solsoft/drupal-boilerplate)
[![Dependency Status](https://www.versioneye.com/user/projects/58fea3616ac17142da9c8a03/badge.svg?style=flat-square)](https://www.versioneye.com/user/projects/58fea3616ac17142da9c8a03)
[![Packagist](https://img.shields.io/packagist/vpre/solsoft/drupal-boilerplate.svg)](https://packagist.org/packages/solsoft/drupal-boilerplate)
[![PHP 7 ready](https://php7ready.timesplinter.ch/solsoft/drupal-boilerplate/8.x/badge.svg)](https://travis-ci.org/solsoft/drupal-boilerplate)
[![License](https://poser.pugx.org/solsoft/drupal-boilerplate/license)](https://github.com/solsoft/drupal-boilerplate/blob/8.x/LICENSE)

Boilerplate for deploying new [Drupal](https://drupal.org/) projects. Based on [drupal-composer/drupal-project](https://github.com/drupal-composer/drupal-project).  
Using [Composer](https://getcomposer.org/) and associated plugins, and the official [packages.drupal.org](http://drupal-composer.org/) repository.  
Packages are published and updated regularly at [Packagist](https://packagist.org/packages/solsoft/drupal-boilerplate).  
Examples are provided for a series of modules and themes and are included in the `composer create-project` process.

## Drupal versions supported
- Drupal 8.0.x - 8.3.x
- Drupal 7.x

Drupal 7.x is being deployed with a Drupal 8 look alike directory structure, using [davidbarratt/drupal-structure](https://github.com/davidbarratt/drupal-structure).

## Development branches
- 8.x - development branch for the latest Drupal 8.3.x
- 7.x - development branch for the latest Drupal 7.x

## Usage
Before starting, consult the Composer reference for standard usage instructions.  
Use `composer create-project` to deploy a new project, specifying one branch.

```
composer create-project solsoft/drupal-boilerplate:8.x-dev my-project --stability dev --no-interaction;
cd my-project;
composer update;
composer show;
composer site-install;
composer site-run;
```

## Contributing
Clone the git repository pointing to one of the development branches.  
Merge requests should be submitted against these branches.

```
git clone -b 8.x git@github.com:solsoft/drupal-boilerplate.git;
cd drupal-boilerplate;
composer install;
composer update;
composer show;
composer site-install;
composer site-run;
```

### Extras
Optionally you may run `composer examples-deploy` to install example Drupal
Contributed libraries, modules and themes example dependencies. These can
also be undeployed by running `composer examples-undeploy`. See more
information about this in the `config/composer.examples` directory.  
Drush can be deployed locally by running `composer drush-deploy`.  
The project can be reset by running `composer cleanup-project` to delete
the `vendor/`, `assets/` and `app/` directories and the `composer.lock` file.

## Credits
See the [composer README](composer/README.md) for information about composer packages.  
Inspiration taken from [drupal-composer/drupal-project](https://github.com/drupal-composer/drupal-project).

Drupal Boilerplate  
Copyright (C) 2016-2017 SOL-Soft  
Luís Pedro Algarvio

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
