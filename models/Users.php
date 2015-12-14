<?php
/**
 * Model UserController
 *
 * @link https://docs.phalconphp.com/en/latest/reference/models.html
 * @author Edvard
 * @time 2015.12.14 12:13
 */
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Message;
use Phalcon\Mvc\Model\Validator\Uniqueness;
use Phalcon\Mvc\Model\Validator\InclusionIn;

class Users extends Model
{

    /**
     * 映射到那张表
     */
    public function getSource()
    {
        return 'user';
    }

    public function validation()
    {

        // 名字唯一
        $this->validate(
        /**
         * @link https://docs.phalconphp.com/en/latest/api/index.html
         */
            new Uniqueness(
                array(
                    "field" => "username",
                    "message" => "The name must be unique"
                )
            )
        );

        // 验证是否失败
        if ($this->validationHasFailed() == true) {
            return false;
        }

    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return User[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return User
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    public function login($username, $password)
    {
        $user = $this->getDetail4Uname($username);

        if ($user->password !== crypt($password, $user->password)) {
            return false;
        }
    }

    public function getDetail4Uname($username)
    {
        return $this->findFirst(array(
            'username' => $username
        ));
    }
}