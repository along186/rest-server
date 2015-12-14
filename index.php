<?php
/**
 * Class index
 * @author Edvard
 * @time 2015.12.14 12:13
 */
use Phalcon\Mvc\Micro;
use Phalcon\Loader;
use Phalcon\DI\FactoryDefault;
use Phalcon\Db\Adapter\Pdo\Mysql as PdoMysql;

try {
    // 加载模块
    $loader = new Loader();

    $loader->registerDirs(
        array(
            __DIR__ . '/models/',
            __DIR__ . '/controller/'
        )
    )->register();

    /**
     * @link https://docs.phalconphp.com/en/latest/reference/di.html
     */
    $di = new FactoryDefault();

    // 设置db
    $di->set('db', function () {
        return new PdoMysql(
            array(
                "host" => "localhost",
                "username" => "root",
                "password" => "",
                "dbname" => "encounter"
            )
        );
    });

    $app = new Micro($di);

    $app->get('/', function () {
        echo "Singou Encounter Back End API Server";
    });

    //改变post可以改变这个接口接收的请求方法
    //把new放在function里面, 减少不需要的实例化
    $app->post('/User/login', function () {
        return router('User', 'login', func_get_args());
    });
    
    //TODO some api
    $app->get('/user/info/:uid:', array($user, "getInfo"));
    $app->post('/user/info/:uid:', array($user, "addUser"));
    $app->put('/user/info/:uid:', array($user, "editUser"));
    $app->delete('', array());

    $app->notFound(function () use ($app) {
        $app->response->setStatusCode(404, "Not Found")->sendHeaders();
        echo 'This is crazy, but this page was not found!';
    });

    $app->handle();
} catch (Exception $e) {
    echo "Exception: ", $e->getMessage();
}

function router($controller, $action, $parameters)
{
    $class_name = $controller . 'Controller';
    $controller = new $class_name;
    return call_user_func(array($controller, $action), $parameters);
}