<?php

namespace common\models;

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
class Upload extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'upload';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'path', 'model_name', 'model_id', 'desc'], 'required'],
            [['id'], 'integer'],
            [['desc'], 'string'],
            [['path', 'model_name', 'model_id'], 'string', 'max' => 255],
            [['path', 'model_name', 'model_id'], 'unique', 'targetAttribute' => ['path', 'model_name', 'model_id']],
            [['id'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'path' => 'Path',
            'model_name' => 'Model Name',
            'model_id' => 'Model ID',
            'desc' => 'Desc',
        ];
    }
    
    
    public function saveImages(){
        
    }
}
