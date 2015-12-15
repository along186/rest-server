<?php

use Phalcon\Mvc\Controller;
use Phalcon\Http\Response;

class BaseController extends Controller
{
    // 暂时设定默认过期时间为两小时
    private static $expire = 7200;
    
    private $_errors = array(
        '0001' => 'page not found',
        '1000' => 'empty username',
        '1001' => 'empty password',
        '1002' => 'user dose not exist',
        '1003' => 'wrong password',
    );
    
    private $_statuses = array(
        200 => 'OK',
        201 => 'CREATED',
        202 => 'ACCEPTED',
        204 => 'NO CONTENT',
        400 => 'INVALID REQUEST',
        401 => 'UNAUTHORIZED',
        403 => 'FORBIDDEN',
        404 => 'NOT FOUND',
        406 => 'NOT ACCEPTABLE',
        410 => 'GONE',
        422 => 'UNPROCESABLE ENTITY',
        500 => 'INTERNAL SERVER ERROR',
    );

    public function response($data = array(), $status = 200, $token = null)
    {
        $response = new Response();

        $response->setStatusCode($status);
        $response->setContent(!empty($this->_statuses[$status]) ? $this->_statuses[$status] : null);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('api-version', '1.0');
        $response->setHeader('singou-token', $token);
        $response->setJsonContent($data);

        return $response;
    }
    
    public function error($errors = array(), $status = 400)
    {
        if (!is_array($errors)) {
            $errors = array($errors);
        }
        
        $data = array(
            'errors' => array()
        );
        
        foreach ($errors as $error) {
            $data['errors'][] = array(
                'code' => $error,
                'message' => !empty($this->_errors[$error]) ? $this->_errors[$error] : 'UNKOWN_ERROR',
            );
        }
        
        return $this->response($data, $status);
    }
    
    public function success($data = array(), $status = 200)
    {
        return $this->response($data, $status);
    }

    /**
     * 获得token
     * @param $userId
     * @param $orgId
     * @param $eventId
     * @return bool 返回false表示生成失败，否则返回sessionId
     */
    protected static function obtainToken($userId, $orgId, $eventId)
    {
        session_start();
        
        $sessionId = session_id();

        $token = new Token();
        
        $token->expire = time() + self::$expire;
        $token->create_time = time();
        $token->token = $sessionId;
        $token->event_id = $eventId;
        $token->user_id = $userId;
        $token->org_id = $orgId;

        if (!$token->save()) {
            return false;
        }

        return $sessionId;
    }


    /**
     * 验证token
     * @return bool true为合法，false为非法
     */
    protected function verifyToken()
    {
        $request = new Request();
        $token = $request->getHeader('singou-token');
        
        //如果token不为空则检查，为空放行
        if (!empty($token)) {
            $model_token = new Token();
            $dbToken = $model_token->findFirst(array(
                'token' => $token
            ));
            $offset = time() - intval($dbToken->expire());
            if ($offset < 0) {
                return false;
            }
            return true;
        }
    }

}
