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

class User extends Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $parent_id;

    /**
     *
     * @var string
     */
    public $username;

    /**
     *
     * @var string
     */
    public $password;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var string
     */
    public $organization;

    /**
     *
     * @var string
     */
    public $title;

    /**
     *
     * @var string
     */
    public $mobile_country_code;

    /**
     *
     * @var string
     */
    public $mobile_number;

    /**
     *
     * @var string
     */
    public $email;

    /**
     *
     * @var integer
     */
    public $sms_privilege;

    /**
     *
     * @var double
     */
    public $deposite_balance;

    /**
     *
     * @var integer
     */
    public $creator;

    /**
     *
     * @var string
     */
    public $created_time;

    /**
     *
     * @var integer
     */
    public $modifier;

    /**
     *
     * @var string
     */
    public $modified_time;

    /**
     *
     * @var integer
     */
    public $isdeleted;

    /**
     *
     * @var integer
     */
    public $deletor;

    /**
     *
     * @var string
     */
    public $deleted_time;

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
        
        return true;
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

    public function login($un, $pwd)
    {
        $user = $this->getDetail4Uname($un);
        
        if(empty($user)) {
            return false;
        }

        if ($user->password != crypt($pwd, $user->password)) {
            return false;
        }
        
        return $user;
    }

    public function getDetail4Uname($username)
    {
        return $this->findFirst(array(
            'username' => $username
        ));
    }
}