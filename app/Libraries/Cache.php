<?php declare(strict_types = 1);

namespace App\Libraries;

use \Config\Services as Services;

class Cache
{
    /**
     * This function checks if data is cached and returns it if it exists, otherwise it calls a callback
     * function to generate the data and caches it.
     *
     * @param callable $callback A callable function that will be executed if the data is not found in the
     *                           cache. The function should return the data that needs to be cached.
     * @param string|int $key    Required parameter, that should, in combination with prefix be unique.
     * @param string $prefix     Optional prefix for the key.
     * @param ?int $ttl          Optional TTL in seconds
     *
     * @return mixed Cached data or newly searched data.
     */
    public static function check(callable $callback, $key, string $prefix = "", ?int $ttl = null) : mixed
    {
        $data = Cache::get($key, $prefix);
        if (is_null($data)) {
            $data = $callback();
            if (!is_null($ttl)) {
                Cache::save($data, $key, $prefix, $ttl);
            } else {
                Cache::save($data, $key, $prefix);
            }
        }
        return $data;
    }

    /**
     * This PHP function deletes a cache item with a given key and prefix.
     *
     * @param string|int $key    Required parameter, that should, in combination with prefix be unique.
     * @param string $prefix     Optional prefix for the key.
     *
     * @return bool Succes or failure
     */
    public static function delete($key, string $prefix = "") : bool
    {
        $cache = Services::cache();
        return $cache->delete(Cache::handlePrefix($key, $prefix));
    }

    /**
     * This function saves a value in the cache with a given key and prefix, and an optional time-to-live
     * (TTL) value.
     *
     * @param value $value       The value to be cached. It can be of any data type that can be serialized.
     * @param string|int $key    Required parameter, that should, in combination with prefix be unique.
     * @param string $prefix     Optional prefix for the key.
     * @param int $ttl           "time to live" and refers to the amount of time that the cache is valid.
     *
     * @return bool Succes or failure
     */
    public static function save($value, $key, string $prefix = "", int $ttl = 600) : bool
    {
        $cache = Services::cache();

        $key = Cache::handlePrefix($key, $prefix);
        if ($cache->get($key) !== null) {
            return false;
        }

        return $cache->save($key, $value, $ttl);
    }

    /**
     * This PHP function gets a cache item with a given key and prefix.
     *
     * @param string|int $key    Required parameter, that should, in combination with prefix be unique.
     * @param string $prefix     Optional prefix for the key.
     *
     * @return mixed Succes or failure
     */
    public static function get($key, string $prefix = "") : mixed
    {
        $cache = Services::cache();
        return $cache->get(Cache::handlePrefix($key, $prefix));
    }

    private static function handlePrefix($key, string $prefix) : string
    {
        $key = (string) $key;
        return $prefix === "" ? $key : ($prefix . '_' . $key);
    }
}
