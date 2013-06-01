<?php

abstract class Configuration
{
    public static function get($key)
    {
        require('config/config.inc.php');

        return $$key;
    }
} 