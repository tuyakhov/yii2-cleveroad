<?php
namespace frontend\models;

use common\models\User;
use yii\base\InvalidParamException;
use yii\base\Model;
use Yii;
use yii\db\ActiveRecord;
use yii\db\ActiveRecordInterface;

/**
 * Signup form
 */
class UserForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $password_repeat;

    /**
     * @var $_user User
     */
    private $_user;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

            ['password', 'required', 'on' => 'signup'],
            ['password', 'string', 'min' => 6],
            ['password', 'compare'],
            ['password_repeat', 'required', 'on' => 'signup'],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new User();
            $user->username = $this->username;
            $user->email = $this->email;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            if ($user->save())
                Yii::$app->mailer->compose('registration', ['user' => $user])
                    ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name . ' robot'])
                    ->setTo($user->email)
                    ->setSubject('Подтверждение регистрации ' . \Yii::$app->name)
                    ->send();
            return $user;
        }

        return null;
    }

    /**
     * Edits user profile
     */
    public function editProfile()
    {
        $this->_user->username = $this->username;
        $this->_user->email = $this->email;
        if (!empty($this->password))
            $this->_user->setPassword($this->password);
        if ($this->validate(array_keys($this->_user->dirtyAttributes)))
            return $this->_user->save();
        return false;
    }

    /**
     * Confirms registration
     *
     * @param string $auth auth key
     * @return static|null
     */
    public static function confirmRegistration($auth)
    {
        $user = User::findByAuthKey($auth);
        if (!$user)
            throw new InvalidParamException('Не верный ключ аутентификации');
        $user->status = User::STATUS_ACTIVE;
        return $user->save();
    }

    public function getUser()
    {
        return isset($this->_user) ? $this->_user : null;
    }

    public function setUser($value)
    {
        if ($value instanceof ActiveRecordInterface) {
            $this->_user = $value;
            $this->username = $this->_user->username;
            $this->email = $this->_user->email;
        }
        else
            throw new InvalidParamException('Wrong value, user variable must be instance of ActiveRecord');
    }
}
