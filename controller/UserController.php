<?php
/**
 * Class UserController
 * @author Edvard
 * @time 2015.12.14 12:13
 */

use Phalcon\Mvc\Model\Query;
use Phalcon\Http\Response;

class UserController extends ControllerBase
{

    /**
     * @link https://docs.phalconphp.com/en/latest/reference/controllers.html#request-and-response
     * @link https://docs.phalconphp.com/en/latest/reference/phql.html
     * 
     **/
    public function login()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        $model_user = new User();
        $result = $model_user->login($username, $password);
        
        if(false == $result){
            return parent::response(array(
                'status' => 'ERROR',
                'messages' => "Access is not authorized"
            ),401,"Access is not authorized");
        }else{
            // 返回的是simple对象，需要注意,如果要取某个字段，需要
            // foreach遍历，而且需要注意，json_encode不能解析simple对象
            
            return parent::response($result);
        }
    }

}
