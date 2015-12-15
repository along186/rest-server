<?php

if (!function_exists('decode_request')) {
    function decode_request($json) {
        foreach (json_decode($json, true) as $key => $value) {
            if (empty($_POST[$key])) {
                $_POST[$key] = $value;
            }
        }
    }
}