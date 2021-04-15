<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;
/**
* @property string $password
 */

class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    public static function findIdentity($id)
    {
        return User::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        // TODO: Implement getAuthKey() method.
    }

    public function validateAuthKey($authKey)
    {
        // TODO: Implement validateAuthKey() method.
    }


    public static function findUserByName($name) {//возвращаем пользователя по имени
        return User::find()->where(['name' => $name])->one();
    }

    public function validatePassword ($password) {
        return ($password == $this->password);
    }

    public function getTeams() {
        return $this->hasMany(Team::className(), ['user_id' =>'id']);
    }
}