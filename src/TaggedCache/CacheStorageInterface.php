<?php

/**
 * This interface is using in Cache class.
 * 
 * See usage examples in README file.
 * See lincense text in LICENSE file.
 * 
 * (c) Evgeniy Udodov <flr.null@gmail.com>
 */

namespace TaggedCache;

/**
 * Cache storage interface.
 */
interface CacheStorageInterface
{
    /**
     * Retrieves data from storage.
     * 
     * @param String $key
     * 
     * @return String|false
     */
    public function get($key);
    
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
    public function set($key, $value, $expire);
    
    /**
     * Removes data from storage.
     * 
     * @param String $key
     * 
     * @return bool 
     */
    public function delete($key);
    
    /**
     * Returns unix timestamp with needed accuracy.
     * For example it may be in ms.
     * This value used for tags validation.
     * 
     * @return int 
     */
    public function getTagValue();
}