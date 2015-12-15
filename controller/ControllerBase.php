<?php

use Phalcon\Mvc\Controller;
use Phalcon\Http\Response;

class ControllerBase extends Controller
{
    public $errorCode = array(
        
    );
    
    public function response($data = array(), $status = 200, $content = null)
    {
        $response = new Response();
        
        $response->setStatusCode($status);
        $response->setContent($content);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('api-version', '1.0');
        
        $response->setJsonContent($data);
        
        return $response;
    }
}
