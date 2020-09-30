<img src="https://cdn2.birdeye.com/version2/containers/header/grey-blue-logo.svg">

# BirdEye Service SDK for Laravel

BirdEye helps your business be found and chosen by new customers,
be connected with your existing customers, and deliver the best end-to-end customer experience.

This is an implementation of the BirdEye REST API designed to be used with 
Laravel PHP Framework v6, 7 and beyond.

Cape & Bay is not affiliated with BirdEye. We prefer to build custom packages for our needs, 
but it may also help you or be a good reference for your own implementation.

## Getting Started

### Quick Links
1. [Installation](#Installation)
    - [Configuration](#Configuration)
2. [Seed the Database](#migrate-the-child-business-data)
3. [Using the Trait](#using-the-trait)
4. [Usage](#Usage)
    - [Check In](#check-an-enduser-customer-into-a-business) 
5. [Change Log](#change-log)
6. [Contributing](#contributing)
7. [Security](#security)
8. [License](#License)

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

#### Using Multiple Accounts
To use multiple accounts, leave `config('birdeye.deets.parent_business_id)` blank.
Instead, populate `config('birdeye.accounts)` like the following example

```php
<?php

return [
    // Other configs left out for brevity
    'accounts' => [
        'client-a' => env('BIRD_EYE_CLIENT_A_KEY', 'something'),
        'client-b' => 'some-token'
    ],
];
```
Finally, run the cron `php artisan birdeye:init` to migrate the child businesses of each parent. 

### Migrate the Child Business Data
Run the built-in CLI Command to migrate the accounts' child business into the ecosystem

```bash
$ php artisan birdeye:init
```
You can run this command again to add new accounts' child businesses, by adding to the config and running again

### Using the Trait
This package is bundle with a simple trait that when coupled with one of your project's 
Eloquent models can link to any of the records in the `birdeye_businesses` table by
use of an Eloquent relation.

Start by Adding the trait to one of your project's models by including the dependancy and
importing the trait `HasBirdEyeBusiness` like so -
```php

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use CapeAndBay\BirdEye\Traits\HasBirdEyeBusiness;

class StoreLocation extends Model
{
    use HasBirdEyeBusiness, SoftDeletes;
   
    public $birdeye_id_column = 'some_column';
 
    /** Rest of the Logic assumed */
}
```

Be sure to include `$birdeye_id_column` where `'some_column'` is the foreign key 
to `birdeye_businesses.internal_id`
<br>
*Note - that in order to complete the relationship you will need to manually 
enter the relationship id or roll your own automated method.*

This exposes an elequent relation `birdeye_business()` that an be used in queries.

To use the trait, peep this example and adapt -
```php

<?php
    use App\LocationModel;

    $location = LocationModel::find(1);  
    $birdeye_biz = $location->birdeye_business()->first();
```
 The contents of the BirdEye Business are available to be used with rest of the package
 Library to ping BirdEye to interface with them!

## Usage
### Check an EndUser Customer into a business
```php

<?php
    use App\StoreLocation; //Uses HasBirdEyeBusiness trait attached
    use CapeAndBay\BirdEye\Facades\BirdEye;

    $location = StoreLocation::find(1);
    $business = $location->birdeye_business()->first();
    $payload = [
       'name' => 'Some Name',
       'emailId' => 'someName@test.com',
       'phone' => '5555551212',
       'smsEnabled' => '1',
   ];
    $birdeye_customer = BirdEye::get('customer', $business->business_id);
    $checkin_response = $birdeye_customer->checkin($payload);

    return $checkin_response;
```


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

MIT. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/capeandbay/birdeye.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/capeandbay/birdeye.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/capeandbay/birdeye/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/12345678/shield

[link-packagist]: https://packagist.org/packages/capeandbay/birdeye
[link-downloads]: https://packagist.org/packages/capeandbay/birdeye
[link-travis]: https://travis-ci.org/capeandbay/birdeye
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://github.com/capeandbay
[link-contributors]: ../../contributors
