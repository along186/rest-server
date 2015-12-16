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

require __DIR__ . '/common/common.lib.php';

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
    

    $app->get('/token', function () {
        return router('User', 'login', func_get_args());
    });
    $app->delete('/token', function () {
        return router('User', 'logout', func_get_args());
    });
    
    
    $app->get('/lottery', function () {
        return router('');
    });
    $app->post('/lottery', function () {
        return router('');
    });
    

    $app->notFound(function () {
        return router('Base', 'error', array('0001', 404));
    });

    $app->handle();
} catch (Exception $e) {
    echo "Exception: ", $e->getMessage();
}

function router($controller, $action, $parameters)
{
    $class_name = $controller . 'Controller';
    $controller = new $class_name;
    return call_user_func_array(array($controller, $action), $parameters);
}