# Railtime

A PHP library for [Irish Rail's Realtime API](http://api.irishrail.ie/realtime/).

## Usage

The main class exported by this package is `\Railtime\API`.

First, create a new `API` object:

```php
use \Railtime\API;

$api = new API;
```

Then, call the various API methods:

```php
$stations = $api->stations();
```

The `API` class exposes the following methods:

    Station[]           stations([$station_type])
    RunningTrain[]      current_trains([$train_type])
    StationPassing[]    station_passings($name_or_code[, $minutes = null])
    TrainMovement[]     train_movements($train_id, $train_date)

Make sure to check out the [Examples](#examples).

## Installing

Once you've [installed Composer](http://getcomposer.org/doc/00-intro.md#installation-nix) simply run:

    php composer.phar install

Or, if you installed composer globally:

    composer install

## Tests [![Build Status](https://api.travis-ci.org/daviddoran/php-railtime.png)](https://travis-ci.org/daviddoran/php-railtime)

The unit tests are contained in `test` and the configuration in `phpunit.xml`.

After installing dependencies with composer, the following should run the tests:

    ./vendor/bin/phpunit

## Examples

### Get a list of all the Dart stations

```php
$stations = $api->stations(\Railtime\StationTypeDart);
```

### Get the currently running Mainline trains

```php
$trains = $api->current_trains(\Railtime\TrainTypeMainline);
```

### Get a list of the trains passing Howth Junction (default next 90 minutes)

```php
//You can use the station's full name
$passings = $api->station_passings("Howth Junction");

//Or use the station's code
$passings = $api->station_passings("HWTHJ");
```

### Get a list of the trains passing Connolly in the next 15 minutes

```php
//You can also use the station code "HWTHJ"
$passings = $api->station_passings("Howth Junction", 15);
```

### Get a list of a train's movements on a particular day

```php
$movements = $api->train_movements("E815", "25 Aug 2013");
```

## License

This project is released under the MIT License - see the LICENSE file for details.
