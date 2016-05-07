# drupal-composer-skeleton
Composer skeleton for Drupal projects.
Using Drupal 7 and 8, Composer and associated plugins, and the [drupal packagist](https://packagist.drupal-composer.org/) repository.

Requires [Composer](https://getcomposer.org/).

## Drupal versions supported
- Drupal 7.x
- Drupal 8.0.x
- Drupal 8.1.x

## Recipes
- Drupal 7.x
- Drupal 7.x-plus (Drupal 8 directory structure look-alike)
- Drupal 8.0.x
- Drupal 8.1.x

## Usage

Consult the Composer reference on standard usage.

Basic commands:
```
composer install
composer update
```

## Credits

The following composer plugins were used:
- [cweagans/composer-patches](https://github.com/cweagans/composer-patches), to allow adding patches to dependencies
- [wikimedia/composer-merge-plugin](https://github.com/wikimedia/composer-merge-plugin), to allow merging composer.json files
- [composer/installers](https://github.com/composer/installers), to differentiate drupal dependencies
- [robloach/component-installer](https://github.com/RobLoach/component-installer), to support components as libraries
- [derhasi/composer-preserve-paths](https://github.com/derhasi/composer-preserve-paths), to preserve and force paths
- [davidbarratt/drupal-structure](https://github.com/davidbarratt/drupal-structure), to make the directory structure of Drupal 6/7 look like Drupal 8
- [drupal-composer/drupal-scaffold](https://github.com/drupal-composer/drupal-scaffold), to add missing files from Drupal 8 distribution

Additional code was used from [drupal-composer/drupal-project](https://github.com/drupal-composer/drupal-project).

Drupal Composer Skeleton  
Copyright (C) 2016 SOL-Soft  
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