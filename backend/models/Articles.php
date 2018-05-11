<?php

namespace backend\models;

use Yii;
use yii\web\UploadedFile; 

/**
 * This is the model class for table "articles".
 *
 * @property int $id
 * @property string $name
 * @property string $filename
 * @property string $type
 * @property string $uploads_path
 * @property int $subcat_id
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 *
 * @property SubCategories $subcat
 */
class Articles extends \yii\db\ActiveRecord
{
    
    public $file;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'articles';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'subcat_id', ], 'required'],
            [['subcat_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['name', 'filename'], 'string', 'max' => 250],
            [['type'], 'string', 'max' => 100],
            [['uploads_path'], 'string', 'max' => 255],
            [['file'], 'file'],
            [['subcat_id'], 'exist', 'skipOnError' => true, 'targetClass' => SubCategories::className(), 'targetAttribute' => ['subcat_id' => 'id']],
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
            'filename' => 'Filename',
            'type' => 'Type',
            'uploads_path' => 'Uploads Path',
            'subcat_id' => 'Subcategory',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubcat()
    {
        return $this->hasOne(SubCategories::className(), ['id' => 'subcat_id']);
    }
}
