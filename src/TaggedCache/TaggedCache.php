<?php

/**
 * This class can be used for simple caching with
 * tags dependencies system.
 * 
 * See usage examples in README file.
 * See lincense text in LICENSE file.
 * 
 * (c) Evgeniy Udodov <flr.null@gmail.com>
 */

namespace TaggedCache;

use \TaggedCache\TaggedCacheErrorException;
use \TaggedCache\TaggedCacheStorageInterface;

/**
 * Main cache class.
 */
class TaggedCache
{
    private $storage;
    private $dependencies = false;
    private $clearDependencies = true;
    private $tagSuffix = "_tag";
    
    /**
     * @param TaggedCacheStorageInterface $storage 
     */
    public function __construct(TaggedCacheStorageInterface $storage) {
        $this->storage = $storage;
    }
    
    /**
     * Retrieves data from storage.
     * 
     * @param String $key
     * 
     * @return mixed|false
     */
    public function get($key) {
        $value = $this->storage->get($key);
        if ($value === false) {
            return false;
        }
        $value = unserialize($value);
        $tags = $value['tags'];
        $value = $value['value'];
        if ($tags === false || (is_array($tags) && !count($tags))) {
            // Key has no dependencies
            return $value;
        }
        // Key has dependencies
        foreach($tags as $tagName => $tagValue) {
            $currentTagValue = $this->storage->get($tagName);
            if ($currentTagValue === false
             || $tagValue != $currentTagValue
            ) {
                return false;
            }
        }
        // Check for corruption of self tag
        $currentTagValue = $this->storage->get($key . $this->tagSuffix);
        if ($currentTagValue === false) {
            return false;
        }
        return $value;
    }
    
    /**
     * Saves data into storage.
     * Replaces it if exists.
     * Also it uses dependency if it has been defined.
     * 
     * @param String $key
     * @param mixed $value
     * @param int $expire
     * 
     * @return bool 
     */
    public function set($key, $value, $expire = 0) {
        $value = array('value'=>$value, 'tags'=>$this->dependencies);
        $valueIsSuccess = $this->storage->set($key, serialize($value), $expire);
        $tagName = $key . $this->tagSuffix;
        $tagIsSuccess = $this->storage->set($tagName, $this->storage->getTagValue(), $expire);
        if ($this->clearDependencies) {
            $this->dependencies = false;
        }
        return ($valueIsSuccess && $tagIsSuccess) ? true : false;
    }
    
    /**
     * Sets dependencies for cache process.
     * 
     * @param Array $params 
     */
    public function setDependencies($tags) {
        if (is_array($tags) && count($tags)) {
            // Build cache dependencies
            $depArray = array();
            foreach($tags as $tagName) {
                if (!is_string($tagName)) {
                    throw new TaggedCacheErrorException('Invalid dependency tags name. String expected.');
                }
                $tagName .= $this->tagSuffix;
                $depArray[$tagName] = $this->storage->get($tagName);
            }
            $this->dependencies = $depArray;
        } else {
            throw new TaggedCacheErrorException('Invalid dependencies tags. Array expected.');
        }
    }
}


/**
 * ErrorException stub.
 */
class TaggedCacheErrorException extends \ErrorException
{
    
}