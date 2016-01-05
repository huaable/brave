<?php

namespace Brave;

/**
 * Class Event
 * @package Brave
 */
class Event
{

    /**
     * @var array
     */
    protected static $events = [];

    /**
     * @param string   $eventName
     * @param callable $callback
     */
    public static function on($eventName, $callback)
    {
        if (!isset(self::$events[$eventName])) {
            self::$events[$eventName] = [];
        }

        if (is_callable($callback)) {
            self::$events[$eventName][] = $callback;
        }
    }

    /**
     * @param string $eventName
     */
    public static function off($eventName)
    {
        unset(self::$events[$eventName]);
    }

    /**
     * @param string $eventName
     * @param array  $data
     */
    public static function trigger($eventName, $data = [])
    {
        if (isset(self::$events[$eventName])) {
            foreach (self::$events[$eventName] as $callback) {
                if (is_callable($callback)) {
                    $callback($data);
                }
            }
        }
    }

}