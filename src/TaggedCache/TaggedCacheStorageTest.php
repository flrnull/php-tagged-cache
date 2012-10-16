<?php

/**
 * Test CacheStorageInterface implementation.
 * This class is using in TaggedCache class.
 * 
 * See usage examples in README file.
 * See lincense text in LICENSE file.
 * 
 * (c) Evgeniy Udodov <flr.null@gmail.com>
 */

namespace TaggedCache;

/**
 * APC cache storage implementation.
 */
class TaggedCacheStorageTest implements TaggedCacheStorageInterface
{
    /**
     * Retrieves data from storage.
     * 
     * @param String $key
     * 
     * @return String|false
     */
    public function get($key) {
        return $GLOBALS[$key];
    }
    
    /**
     * Saves data into storage.
     * Replaces it if exists.
     * 
     * @param String $key
     * @param String $value
     * @param int $expire
     * 
     * @return bool 
     */
    public function set($key, $value, $expire) {
        return $GLOBALS[$key] = $value;
    }
    
    /**
     * Removes data from storage.
     * 
     * @param String $key
     * 
     * @return bool 
     */
    public function delete($key) {
        $GLOBALS[$key] = false;
        return true;
    }
    
    /**
     * Returns unix timestamp with needed accuracy.
     * For example it may be in ms.
     * This value used for tags validation.
     * 
     * @return int 
     */
    public function getTagValue() {
        return ceil(microtime(true)*1000); 
    }
}