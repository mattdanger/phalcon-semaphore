# phalcon-semaphore

Simple semaphore component for Phalcon PHP

## Requirements

* Phalcon v1.4.x

## Installation ##

Install using Composer:

```json
{
	"require": {
		"mattdanger/phalcon-semaphore": "1.*"
	}
}
```

Create a database table `semaphore` with this schema:

```sql
CREATE TABLE `semaphore` (
  `name` varchar(255) NOT NULL DEFAULT '',
  `value` varchar(255) DEFAULT NULL,
  `timestamp` datetime DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
```

Then add the service:

```php
$di->set('semaphore', '\PhalconSemaphore\Semaphore');
```

If you want to use a different database table name you can initialize the service like this:

```php
$di->set('semaphore', function() use ($config){
  return new \PhalconSemaphore\Semaphore('my_table_name');
}, true);
```

## Usage

```php

$this->semaphore->run(string $class, $string method, int $expiration_hours, array $args);

// Example without method parameters
$this->semaphore->run('MyNamespace\Models\Stat', 'calculate', 1);

// Example with method parameters
$this->semaphore->run('MyNamespace\Models\Stat', 'calculate', 1, array($arg1, $arg2, ...));
```
