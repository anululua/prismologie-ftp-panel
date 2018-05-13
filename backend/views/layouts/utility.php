<?php

use mdm\admin\models\User;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

?>

    <div class="row">
        <?php
        
        $users=User::find()->all();
        $listData=ArrayHelper::map($users,'id','username');

        ?>
            <p>
                <?= Html::a('Create Folders', ['create'], ['class' => 'btn btn-success']) ?>

                    <?= Html::a('Upload Files', ['upload'], ['class' => 'btn btn-success']) ?>
            </p>
            <div class="assigment-form">

                <?php
                
                echo Html::dropDownList('user_list', null,$listData,['prompt' => '--- select user ---','class'=>''], array('label' => 'Users'));
                
                echo Html::checkboxList('postIds', null, array('1'=>'Create Folder','2'=>'Download Files','3'=>'Upload Files'), ['class' => 'checkbox post-type-menu-item pull-right', 'separator' => ' ']);

                ?>
            </div>
            <?php

 $this->beginWidget('zii.widgets.jui.CJuiDialog',array(
    'id'=>'mydialog',
    // additional javascript options for the dialog plugin
    'options'=>array(
        'title'=>'Dialog box 1',
        'autoOpen'=>false,
    ),
));

    echo 'dialog content here';

$this->endWidget('zii.widgets.jui.CJuiDialog');

// the link that may open the dialog
echo CHtml::link('open dialog', '#', array(
   'onclick'=>'$("#mydialog").dialog("open"); return false;',
));
?>
                <div id="simple-div"></div>



    </div>
