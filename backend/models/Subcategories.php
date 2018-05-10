<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "sub_categories".
 *
 * @property int $id
 * @property string $name
 * @property int $cat_id
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Categories $cat
 * @property Articles[] $articles 
 */
class SubCategories extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sub_categories';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'cat_id'], 'required'],
            [['cat_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 250],
            [['cat_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categories::className(), 'targetAttribute' => ['cat_id' => 'id']],
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
            'cat_id' => 'Cat ID',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCat()
    {
        return $this->hasOne(Categories::className(), ['id' => 'cat_id']);
    }
    
    
    /**
    * @return \yii\db\ActiveQuery
    */
   public function getArticles() 
   { 
       return $this->hasMany(Articles::className(), ['subcat_id' => 'id']); 
   } 
    
}
