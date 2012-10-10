PHP Tagged Cache | Simply improves cache system
===========================================

This class helps to validate cache with tagging approach.
If you want to set dependency for some value in cache, you may specify array of needed keys as it dependencies.

Usage example
--------

```php
// Include all files before, if you don't use autoload
use \TaggedCache\TaggedCacheStorageTest;
use \TaggedCache\TaggedCache;

$storage = new TaggedCacheStorageTest;
$cache = new TaggedCache($storage);

// Some params which we want to cache
$key1 = 'foo';
$value1 = 10;
$key2 = 'bar';
$value2 = 15;

// Cache first param
$cache->set($key1, $value1);
// Cache second param  with dependency to first param
$cache->setDependencies(array($key1));
$cache->set($key2, $value2);

// All should be ok now
echo ($cache->get($key2) === $value2) ? 'Ok' : 'Error';

// Now we change first param and check again
usleep(2000); // We use accuracy 1 millisecond in TaggedCacheStorageTest
$cache->set($key1, 9);
echo ($cache->get($key2) === $value2) ? 'Error' : 'Ok';
```