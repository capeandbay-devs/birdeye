<img src="https://cdn2.birdeye.com/version2/containers/header/grey-blue-logo.svg">

# BirdEye Service SDK for Laravel

BirdEye helps your business be found and chosen by new customers,
be connected with your existing customers, and deliver the best end-to-end customer experience.

This is an implementation of the BirdEye REST API designed to be used with Laravel PHP Framework v6, 7 and beyond.

Cape & Bay is not affiliated with BirdEye. We prefer to build custom packages for our needs, but it may also
help you or be a good reference for your own implementation.

## Getting Started

### Installation

Via Composer

``` bash
$ composer require capeandbay/birdeye
```

The package will automatically register itself.
You can optionally publish the config file with:
```bash
php artisan vendor:publish --provider="CapeAndBay\BirdEye\CBBirdEyeServiceProvider" --tag="config"
```
The settings can be found in the generated `config/birdeye.php` configuration file. .

You can publish the migration with:
```bash
php artisan vendor:publish --provider="CapeAndBay\BirdEye\CBBirdEyeServiceProvider" --tag="migrations"
```

If you need to, update the config, before running the migration.
After publishing the migration you can create the `birdeye_businesses` table by running the migrations:

```bash
php artisan migrate
```

### Configuration

By default, the package uses the following environment variables to auto-configure the plugin without modification:
```
BIRDEYE_API_KEY (default = '')
BIRDEYE_PARENT_BUSINESS_KEY (default = '')
BIRDEYE_API_URL (default = 'https://api.birdeye.com/resources') Can be set to their Mock & Debug URLs as per their API
DB_CONNECTION (default = mysql)
```

```php
<?php

return [
    'api_url' => env('BIRDEYE_API_URL','https://api.birdeye.com/resources'),
    'deets' => [
        'api_key' => env('BIRDEYE_API_KEY', ''),
        'parent_business_id' => env('BIRDEYE_PARENT_BUSINESS_KEY', '') // Leave blank if using multiple accounts
    ],
    'accounts' => [],
    'class_maps' => [],
];
```

Note that you can always swap out preloaded classes with a project's arbitrary own; 
the package will use that class in that context.

## Usage
### Migrate the Child Business Data
Run the built-in CLI Command to migrate the accounts' child business into the ecosystem

```bash
$ php artisan birdeye:init
```
You can run this command again to add new accounts' child businesses, by adding to the config and running again

### Check an EndUser Customer into a business


## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Security

If you discover any security related issues, please email angel@capeandbay.com instead of using the issue tracker.

## Credits

- [Angel Gonzalez][link-author]
- [All Contributors][link-contributors]

## License

Proprietary. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/capeandbay/shipyard.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/capeandbay/shipyard.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/capeandbay/shipyard/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/12345678/shield

[link-packagist]: https://packagist.org/packages/capeandbay/shipyard
[link-downloads]: https://packagist.org/packages/capeandbay/shipyard
[link-travis]: https://travis-ci.org/capeandbay/shipyard
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://github.com/capeandbay
[link-contributors]: ../../contributors
