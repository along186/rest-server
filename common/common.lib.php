<?php
decode_request();

function decode_request()
{
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    
    foreach ($data as $key => $value) {
        $_REQUEST[$key] = $value;
    }
}