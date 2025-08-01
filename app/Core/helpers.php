<?php

if (!function_exists('config')) {
    function config(string $key, $default = null){
        static $config;

        if (!$config) {
            $config = require __DIR__ . '/../../config/config.php';
        }

        // Kalau tidak ada key, return seluruh config
        if (is_null($key)) {
            return $config;
        }

        $segments = explode('.', $key);
        $value = $config;

        foreach ($segments as $segment) {
            if (!isset($value[$segment])) {
                return $default;
            }
            $value = $value[$segment];
        }

        return $value;
    }
}

