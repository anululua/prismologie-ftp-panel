<?php

namespace backend\models;

use Yii;
//use mdm\admin\models\User;
use common\models\User;
use yii\base\Model;

/**
 * Description of ChangePassword
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class ChangePassword extends Model
{
    public $oldPassword;
    public $newPassword;
    public $retypePassword;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['oldPassword', 'newPassword', 'retypePassword'], 'required'],
            [['oldPassword'], 'validatePassword'],
            [['newPassword'], 'string', 'min' => 6],
            [['retypePassword'], 'compare', 'compareAttribute' => 'newPassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     */
    public function validatePassword()
    {
        /* @var $user User */
        $user = Yii::$app->user->identity;
        echo '<pre>';
        print_r($user);
        if (!$user || !$user->validatePassword($this->oldPassword)) {
            $this->addError('oldPassword', 'Incorrect old password.');
        }
    }

    /**
     * Change password.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function change($user_id)
    {
        if ($this->validate()) {
            /* @var $user User */
            //$user = Yii::$app->user->identity;
            $user = User::findOne(['id' => $user_id, 'status' => self::STATUS_ACTIVE]);
            $user->setPassword($this->newPassword);
            $user->generateAuthKey();
            if ($user->update()) {
                return true;
            }
        }

        return false;
    }
}
