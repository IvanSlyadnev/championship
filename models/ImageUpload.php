<?php

namespace app\models;

use Yii;
use yii\base\Model;

class ImageUpload extends Model
{
    public $image;

    public function rules()
    {
        return [
            [['image'], 'file', 'extensions'=>'jpg,png,jpeg']
        ];
    }


    public function UploadedFile($file, $currentImage) {
        $this->image = $file;

        if ($this->validate()) {

            $this->deleteImage($currentImage);

            return $this->saveImage();

        }
    }

    public function saveImage() {
        $filename = $this->genereteFileName($this->image);//Генерируем название файла

        $this->image->saveAs(\Yii::getAlias(''). 'uploads/' .$filename);//Сохраняем image в папку

        return $filename;
    }

    private function getFolder() {
        return Yii::getAlias('') . 'uploads/';
    }

    public function deleteImage($image) {
        if ($this->fileExists($image)) {
            unlink($this->getFolder().$image);
        }
    }

    public function fileExists($file) {
        if (!empty($file) && $file !== null) {
            return file_exists($this->getFolder().$file);
        }
    }

    private function genereteFileName ($file) {
        return strtolower(md5(uniqid($file->baseName)) . '.' . $file->extension);
    }



}