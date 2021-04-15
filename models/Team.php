<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "team".
 *
 * @property int $id
 * @property string $name
 * @property string $image
 * @property int $power
 * @property int $user_id
 *
 * @property User $user
 */
class Team extends \yii\db\ActiveRecord
{
    public $oder = [];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'team';
    }

    public function setOder() {
        if ($this->index_group == 0) {
            $this->oder = [1,2,3];
        } else if ($this->index_group == 1) {
            $this->oder = [0,3,2];
        } else if ($this->index_group == 2) {
            $this->oder = [3,0,1];
        } else {
            $this->oder = [2,1,0];
        }
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'power', 'user_id'], 'required'],
            [['power', 'user_id'], 'integer'],
            [['name'], 'string', 'max' => 32],
            [['image'], 'string', 'max' => 100],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'image' => 'Image',
            'power' => 'Power',
            'user_id' => 'User ID',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getBasket() {
        return $this->hasOne(Basket::className(), ['id'=>'basket_id']);
    }

    public function saveImage($image){
        $this->image = $image;
        return $this->save(false);
    }

    public function saveBasket($basket_id) {
        $this->basket_id = $basket_id;
        return $this->save();
    }

    public function getImage() {
        return ($this->image) ? '/championship/web/uploads/'.$this->image : '/championship/web/uploads/no-image.jpg';
    }
}
