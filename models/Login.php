<?php
namespace app\models;
use yii\base\Model;

use Yii;

class Login extends Model {
    public $name;
    public $password;


    function rules() {
        return [
            [['name', 'password'], 'required'],
            ['password', 'validatePassword'],
        ];
    }

    public function validatePassword($attribute, $params) {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user) {
                $this->addError($attribute, 'User not found');
            } else if (!$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Password is wrong');
            }
        }
    }


    public function getUser() {
        return User::findUserByName($this->name);
    }

    public function login() {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser());
        }
        return false;
    }
}

?>