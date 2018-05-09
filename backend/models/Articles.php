<?php

namespace backend\models;

use Yii;

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
 * @property Subcategories $subcat
 */
class Articles extends \yii\db\ActiveRecord
{
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
            [['name', 'filename', 'type', 'uploads_path', 'subcat_id', 'created_at', 'updated_at'], 'required'],
            [['subcat_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['name', 'filename'], 'string', 'max' => 250],
            [['type'], 'string', 'max' => 100],
            [['uploads_path'], 'string', 'max' => 255],
            [['subcat_id'], 'exist', 'skipOnError' => true, 'targetClass' => Subcategories::className(), 'targetAttribute' => ['subcat_id' => 'id']],
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
            'subcat_id' => 'Subcat ID',
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
        return $this->hasOne(Subcategories::className(), ['id' => 'subcat_id']);
    }
}
