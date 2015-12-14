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

    public function login()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        $model_user = new Users();
        $user = $model_user->login($username, $password);

        if(false == $user){
            parent::response(array(
                'status' => 'ERROR',
                'messages' => "Access is not authorized"
            ),401,"Access is not authorized");
        }else
            return parent::response($user);
    }

    /**
     * @link https://docs.phalconphp.com/en/latest/reference/controllers.html#request-and-response
     * @return Response
     */
    public function login2()
    {
        $name = $this->request->getPost('name');
        $password = $this->request->getPost('password');

        // @link https://docs.phalconphp.com/en/latest/reference/phql.html
        // $user = User::find(
        //     array(
        //         "name" => $name,
        //         "password" => $password
        //     )
        // );

        // 上面那种也可以用，但是建议用下面这种
        $phql = "SELECT * FROM Users where name = :name: and password = :password: ";
        $users = $this->modelsManager->executeQuery($phql, array(
            'name' => $name,
            'password' => $password
        ));

        $data = array();
        foreach ($users as $user) {
            $data[] = array(
                'id' => $user->id,
                'name' => $user->name
            );
        }

        // 创建response
        $response = new Response();
        if ($users == false) {
            $response->setStatusCode(401, "Unauthorized"); // 可以改变http code
            $response->setContent("Access is not authorized"); // 以及定义content

            // 错误的信息，从User model抛出来的
//            $errors = array();
//            foreach ($users->getMessages() as $message) {
//                $errors[] = $message->getMessage();
//            }

            $response->setJsonContent(
                array(
                    'status' => 'ERROR',
                    'messages' => "Access is not authorized"
                )
            );

        } else {
            // 默认是200，这里则不用设置
            $response->setJsonContent(
                array(
                    'status' => 'FOUND',
                    'data' => $data
                )
            );
        }

        return $response;
    }

}
