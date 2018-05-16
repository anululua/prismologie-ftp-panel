<?php

use mdm\admin\models\User;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;


$users = User::find()->where('id != 1')->all();

$listData=ArrayHelper::map($users,'id','username');

?>

    <div class="assigment-form">
        <?php
echo Html::dropDownList('user_list', null,$listData,['prompt' => '--- select user ---','class'=>''], array('label' => 'Users'));

echo Html::checkboxList('postIds', null, array('1'=>'Create Folder','2'=>'Download Files','3'=>'Upload Files'), ['class' => 'checkbox post-type-menu-item pull-right', 'separator' => ' ']);?>
    </div>
