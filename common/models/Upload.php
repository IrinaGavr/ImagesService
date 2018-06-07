<?php

namespace common\models;

//use yii\web\UploadedFile;
use Yii;

/**
 * This is the model class for table "upload".
 *
 * @property int $id
 * @property string $path
 * @property string $model_name
 * @property string $model_id
 * @property string $desc
 */
class Upload extends \yii\db\ActiveRecord {

    public $file;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'upload';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['model_name', 'model_id'], 'required'],
                [['desc'], 'string'],
                [['path', 'model_name', 'model_id'], 'string', 'max' => 255],
                [['path', 'model_name', 'model_id'], 'unique', 'targetAttribute' => ['path', 'model_name', 'model_id']]
        ];
    }

    public function beforeSave($insert) {
        // set_time_limit(0);
        if ($this->getIsNewRecord()) {
            $this->path = md5(time() . $this->file->baseName . $this->model_id . $this->model_name) . '.' . $this->file->extension;
        }
        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'path' => 'Path',
            'model_name' => 'Model Name',
            'model_id' => 'Model ID',
            'desc' => 'Desc',
        ];
    }

    public function afterSave($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes);
        if (!$this->upload() && $this->isNewRecord) {
            $this->delete();
        } 
        Yii::$app->session->setFlash('success', 'Запись сохранена');
    }

    public static function getFullPath($path) {
        return \Yii::getAlias('@frontend/web/uploads/') . $path;
    }

    public function upload() {

        if (!empty($this->file) && $this->validate()) {
            return $this->file->saveAs(self::getFullPath($this->path));
        } return FALSE;
    }

    public function getImage() {
        return $image = '/uploads/' . $this->path;
    }

}
