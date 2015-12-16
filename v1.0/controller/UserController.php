<?php
/**
 * Class UserController
 * @author Edvard
 * @time 2015.12.14 12:13
 */

use Phalcon\Mvc\Model\Query;
use Phalcon\Http\Response;

class UserController extends BaseController
{

    /**
     * @link https://docs.phalconphp.com/en/latest/reference/controllers.html#request-and-response
     * @link https://docs.phalconphp.com/en/latest/reference/phql.html
     *
     **/
    public function login()
    {
        $username = $this->request->get('username');
        $password = $this->request->get('password');

        $model_user = new User();

        $result = $model_user->login($username, $password);
        
        if (!is_a($result, 'User')) {
            return $this->error($result);
        }
        
        $model_event = new Event();
        $orgId = $result->parent_id == null ? $result->id : $result->parent_id;
        $event = $model_event->findFirst(array(
            'org_id' => $orgId
        ));

        $token = $this->obtainToken($result->id, $orgId, $event->id);
        
        return $this->success(array(
            'token' => $token
        ));
    }
}
