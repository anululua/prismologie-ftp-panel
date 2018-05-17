<?php

use mdm\admin\models\User;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;


$users = User::find()->where('id !='.Yii::$app->user->getId())->all();

$listData=ArrayHelper::map($users,'id','username');

?>

    <div class="assigment-form">
        <?php 
    
echo Html::dropDownList('user_list', null,$listData,['prompt' => '--- select user ---','class'=>''], array('label' => 'Users'));

//echo Html::checkboxList('postIds', null, array('1'=>'Manage Utilities','2'=>'Public Access',), ['class' => 'checkbox-inline', 'separator' => '']);
        
        ?>

        <label class="checkbox-inline"><input type="checkbox" value="1" name="postIds[]">Manage Utilities</label>
        <label class="checkbox-inline"><input type="checkbox" value="2" name="postIds[]">Public Access</label>
        <button type="submit" class="btn btn-default btn-xs">Save</button>

    </div>
