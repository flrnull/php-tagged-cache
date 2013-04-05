PHP Tagged Cache | Simply improves cache system
===========================================

This class helps to validate cache with tagging approach.
If you want to set dependency for some value in cache, you may specify array of needed keys as it dependencies.

Usage example
--------

```php

use \TaggedCache\CacheStorageTest;
use \TaggedCache\Cache;

$storage = new CacheStorageTest();
$cache = new Cache($storage);

$key1 = 'foo';
$value1 = 10;
$key2 = 'bar';
$value2 = 15;

// Store first param
$cache->set($key1, $value1);

// Store second param  with dependency to first param
$cache->setDependencies(array($key1));
$cache->set($key2, $value2);

// All should be ok now
echo ($cache->get($key2) === $value2) ? 'Ok' : 'Error';

// Now we change first param and check again
usleep(2000); // We use accuracy 1 millisecond in CacheStorageTest
$cache->set($key1, 9);
echo ($cache->get($key2) === false) ? 'Ok' : 'Error';
```