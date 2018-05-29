<?php

namespace backend\models;

use Yii;
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
    public $newPassword;
    public $retypePassword;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['newPassword', 'retypePassword'], 'required'],
            [['newPassword'], 'string', 'min' => 6],
            [['retypePassword'], 'compare', 'compareAttribute' => 'newPassword'],
        ];
    }



    /**
     * Change password.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function change($user_id)
    {
        if ($this->validate()) {
            $user = User::findOne(['id' => $user_id, 'status' => 10]);
            $user->setPassword($this->newPassword);
            $user->generateAuthKey();
            if ($user->update()) {
                return true;
            }
        }

        return false;
    }
}
