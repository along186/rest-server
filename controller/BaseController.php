<?php

use Phalcon\Mvc\Controller;
use Phalcon\Http\Response;

class BaseController extends Controller
{
    // 暂时设定默认过期时间为两小时
    private static $expire = 7200;
    public $errorCode = array();

    public function response($data = array(), $status = 200, $content = null, $token = null)
    {
        $response = new Response();

        $response->setStatusCode($status);
        $response->setContent($content);
        $response->setHeader('Content-type', 'application/json');
        $response->setHeader('api-version', '1.0');
        $response->setHeader('singou-token', $token);
        $response->setJsonContent($data);

        return $response;
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
        $sessionId = session_regenerate_id();

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
        // 如果token不为空则检查，为空放行
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
