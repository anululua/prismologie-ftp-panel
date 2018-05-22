<?php

namespace backend\models;

use Yii;
use common\models\User;

/**
 * This is the model class for table "folders".
 *
 * @property int $id
 * @property int $user_id
 * @property string $utility_name
 * @property string $manage_utilities
 * @property string $public_access
 *
 * @property User $user
 */
class Folders extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'folders';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'], 
            [['utility_name', 'manage_utilities',], 'required'],
            [['manage_utilities', 'public_access'], 'string'],
            [['utility_name'], 'string', 'max' => 500],
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
            'user_id' => 'User ID',
            'utility_name' => 'Utility Name',
            'manage_utilities' => 'Manage Utilities',
            'public_access' => 'Public Access',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
