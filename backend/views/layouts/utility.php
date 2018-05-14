<?php

use mdm\admin\models\User;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\jui\Dialog;
use yii\web\JsExpression;
use yii\helpers\Url;




?>

    <div class="row">
        <?php
        
        $users=User::find()->all();
        $listData=ArrayHelper::map($users,'id','username');

        ?>
            <p>
                <?= Html::a('Create Folder','#',array(
        'onclick'=>'$("#folderdialog").dialog("open");','class'=>'btn btn-success',)); ?>

                    <?= Html::a('Upload Files', ['upload'], ['class' => 'btn btn-success']) ?>
            </p>
            <div class="assigment-form">
                <?php
                echo Html::dropDownList('user_list', null,$listData,['prompt' => '--- select user ---','class'=>''], array('label' => 'Users'));
                
                echo Html::checkboxList('postIds', null, array('1'=>'Create Folder','2'=>'Download Files','3'=>'Upload Files'), ['class' => 'checkbox post-type-menu-item pull-right', 'separator' => ' ']);
                ?>
            </div>

            <?php
        
            Dialog::begin([
            'id'=>'folderdialog',
            'clientOptions' => [
            'modal' => true,
            'title'=>"Add new folder",
            'autoOpen'=>false,
            'dialogClass'=>'no-close',
            'buttons'=> [ 
                [ 
                    'text'=>Yii::t('app', 'cancel'), 
                    'class'=>'btn btn-sm btn-success', 
                    'click'=>new JsExpression(' function() { 
                    $( this ).dialog( "close" ); 
                    }') 
                ],
            ],
          ],
        ]); 
        
        echo '<div class="dialog_input"><input type="text" id="folder_name" name ="folder_name"/></div>';

        
        echo '<button type="submit" id="submitReset" class="btn-sm btn-success">Add</button>';

        Dialog::end();
 
        

        ?>
    </div>
