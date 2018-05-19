# Dhiraagu Bulk SMS Notification Channel for Laravel 5.3+

[![Latest Version on Packagist](https://img.shields.io/packagist/v/dash8x/dhiraagu-sms-notification.svg?style=flat-square)](https://packagist.org/packages/dash8x/dhiraagu-sms-notification)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/dash8x/dhiraagu-sms-notification/master.svg?style=flat-square)](https://travis-ci.org/dash8x/dhiraagu-sms-notification)
[![StyleCI](https://styleci.io/repos/:style_ci_id/shield)](https://styleci.io/repos/:style_ci_id)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/:sensio_labs_id.svg?style=flat-square)](https://insight.sensiolabs.com/projects/:sensio_labs_id)
[![Quality Score](https://img.shields.io/scrutinizer/g/dash8x/dhiraagu-sms-notification.svg?style=flat-square)](https://scrutinizer-ci.com/g/dash8x/dhiraagu-sms-notification)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/dash8x/dhiraagu-sms-notification/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/dash8x/dhiraagu-sms-notification/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/dash8x/dhiraagu-sms-notification.svg?style=flat-square)](https://packagist.org/packages/dash8x/dhiraagu-sms-notification)

This package makes it easy to send notifications using [Dhiraagu Bulk SMS Gateway](https://github.com/dash8x/dhiraagu-sms) with Laravel 5.3.


## Contents

- [Installation](#installation)
	- [Setting up the Dhiraagu Bulk Sms Gateway credentials](#setting-up-the-dhiraagu-bulk-sms-gateway-credentials)
- [Usage](#usage)
	- [Available Message methods](#available-message-methods)
	- [Checking delivery status](#checking-delivery-status)
- [Changelog](#changelog)
- [Testing](#testing)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)
- [Disclaimer](#disclaimer)


## Installation

You can install the package via composer:

```bash
composer require dash8x/dhiraagu-sms-notification
```

Add the service provider (only required on Laravel 5.4 or lower):

```php
// config/app.php
'providers' => [
    ...
    Dash8x\DhiraaguSmsNotification\DhiraaguSmsNotificationServiceProvider::class,
],
```

Optionally add the facade.
```php
// config/app.php
'aliases' => [
    ...
    'DhiraaguSms' => Dash8x\DhiraaguSmsNotification\Facades\DhiraaguSms::class,
],
```

### Setting up the Dhiraagu Bulk Sms Gateway credentials

Add your Dhiraagu Bulk SMS Gateway Account Username, Password, and Url (optional) to your `config/services.php`:

```php
// config/services.php
...
'dhiraagu' => [
    'username' => env('DHIRAAGU_SMS_USERNAME'), // Bulk SMS gateway username, usually same as your sender name 
    'password' => env('DHIRAAGU_SMS_PASSWORD'), // Bulk SMS gateway password
    'url' => env('DHIRAAGU_SMS_URL'), // optional, use only if you need to override the default,
                                      // defaults to https://bulkmessage.dhiraagu.com.mv/partners/xmlMessage.jsp   
],
...
```

## Usage

Now you can use the channel in your `via()` method inside the notification:

```php
use Dash8x\DhiraaguSmsNotification\DhiraaguSmsNotificationChannel;
use Illuminate\Notifications\Notification;

class AccountApproved extends Notification
{
    public function via($notifiable)
    {
        return [DhiraaguSmsNotificationChannel::class];
    }

    public function toDhiraagu($notifiable)
    {
        return "Your {$notifiable->service} account was approved!";
    }
}
```

In order to let your Notification know which phone are you sending to, the channel will look for the phone_number attribute of the Notifiable model. If you want to override this behaviour, add the routeNotificationForDhiraagu method to your Notifiable model.

```php
public function routeNotificationForDhiraagu()
{
    return '+9607777777';
}
```

It also supports sending to multiple phone numbers.

```php
public function routeNotificationForDhiraagu()
{
    return ['+9607777777', '+9609999999'];
}
```

### Available Message methods

#### DhiraaguSmsNotificationMessage

- `setNumbers('')`: Accepts phone numbers to use as the notification recipients.
- `setMessage('')`: Accepts a string value for the notification body.
- `getNumbers()`: Returns the recipients.
- `getMessage()`: Returns the string value of the notification body.

### Checking delivery status

To proccess any laravel notification channel response check [Laravel Notification Events](https://laravel.com/docs/5.6/notifications#notification-events)
This channel returns a `DhiraaguSmsMessage` response object.

Check [dash8x/dhiraagu-sms](https://github.com/dash8x/dhiraagu-sms#check-sms-delivery-status) for Delivery Status Checking Documentation.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email arushad@javaabu.com instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Arushad Ahmed](https://github.com/dash8x)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Disclaimer

This package is not in any way officially affiliated with Dhiraagu.
The "Dhiraagu" name has been used under fair use.