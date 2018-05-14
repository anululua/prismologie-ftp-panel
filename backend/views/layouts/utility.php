<?php

use mdm\admin\models\User;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\jui\Dialog;
use yii\web\JsExpression;
use yii\helpers\Url;
use yii\web\YiiAsset



?>

  <div class="row">
    <?php
        
        $users=User::find()->all();
        $listData=ArrayHelper::map($users,'id','username');

        ?>
      <p>
        <?= Html::a('Create Folder','#',array(
        'onclick'=>'$("#folderdialog").dialog("open");','class'=>'btn btn-success',)); ?>

          <?= Html::a('Upload Files','#',array(
        'onclick'=>'$("#filesdialog").dialog("open");','class'=>'btn btn-success',)); ?>
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
          ],
        ]); 
        
        echo '<div class="dialog_input"><input type="text" id="folder_name" name ="folder_name"/></div>';

        echo '</br>';
        echo '<button type="submit" id="submit_folder" class="btn btn-success">Add</button>';
        echo '<button type="reset" id="reset_folder" class="btn btn-success pull-right">Cancel</button>';

      Dialog::end();
 
        ?>

        <?php
        
            Dialog::begin([
            'id'=>'filesdialog',
            'clientOptions' => [
            'modal' => true,
            'title'=>"Add file",
            'autoOpen'=>false,
            'dialogClass'=>'no-close',
            'width'=> 'auto',
          ],
        ]); ?>

          <form id="data" method="post" enctype="multipart/form-data">
            <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
            <div class="form-group">
              <label>File</label>
              <input class="form-control" name="file" id="fileUpload" type="file">
            </div>
            <div class="form-group">
              <button type="submit" id="submit_files" class="btn btn-success">Add</button>
              <button type="reset" id="reset_files" class="btn btn-success pull-right">Cancel</button>
            </div>
          </form>

          <?php Dialog::end(); ?>
  </div>