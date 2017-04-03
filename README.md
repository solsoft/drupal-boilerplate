# drupal-composer-skeleton
Composer skeleton for Drupal projects. Based on [drupal-composer/drupal-project](https://github.com/drupal-composer/drupal-project).
Using [Drupal](https://drupal.org/), [Composer](https://getcomposer.org/) and associated plugins, and the official [packages.drupal.org](http://drupal-composer.org/) repository.

## Drupal versions supported
- Drupal 8.3.x
- Drupal 8.2.x
- Drupal 8.1.x
- Drupal 8.0.x
- Drupal 7.x

Drupal 7.x is being deployed with a Drupal 8 look alike directory structure, using [davidbarratt/drupal-structure](https://github.com/davidbarratt/drupal-structure).

## Branches
- 8.3.x   - releases branch for Drupal 8.3.x
- 8.2.x   - releases branch for Drupal 8.2.x
- 8.1.x   - releases branch for Drupal 8.1.x
- 8.0.x   - releases branch for Drupal 8.0.x
- 7.x     - releases branch for Drupal 7.x
- 8.x-dev - development branch for latest Drupal 8.x
- 7.x-dev - development branch for latest Drupal 7.x

## Usage

Before starting, consult the Composer reference for standard usage instructions.

```
composer create-project solsoft/drupal-composer-skeleton:8.3.x my-project --stability dev --no-interaction;
cd my-project;
composer install;
composer update;
composer show;
```

## Contributing

Clone the repository using the 8.x branch.
Merge requests should be submitted against this branch.

```
git clone -b 8.x git@github.com:solsoft/drupal-composer-skeleton.git;
cd drupal-composer-skeleton;
composer install;
composer update;
composer show;
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

Forked from [drupal-composer/drupal-project](https://github.com/drupal-composer/drupal-project).

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
