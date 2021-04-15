<?php


namespace app\models;

use Yii;
use yii\base\Model;
class Signup extends Model
{
    public $name;
    public $password;

    function rules() {
        return [
            [['name', 'password'], 'required'],
            [['name'], 'unique', 'targetClass' =>'app\models\User']
        ];
    }

    public function getUser() {
        return User::findOne(['name' =>$this->name]);
    }

    public function signup() {
        $user = new User();
        $user->name = $this->name;
        $user->password = $this->password;
        $user->save();
        $this->login();
        return true;
    }

    public function login() {
        return Yii::$app->user->login($this->getUser());
    }
}