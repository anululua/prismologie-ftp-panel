<?php

use yii\helpers\Html;
use yii\jui\Dialog;
use yii\web\JsExpression;
use yii\helpers\Url;
use yii\web\YiiAsset

?>
    <div class="col-md-3 pull-right" id= "utility_creation">
        <?= Html::a('Create Folder','#',array(
        'onclick'=>'$("#folderdialog").dialog("open");','class'=>'btn btn-success')); ?>

            <?= Html::a('Upload Files','#',array(
        'onclick'=>'$("#filesdialog").dialog("open");','class'=>'btn btn-success')); ?>
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
        
        echo '<div class="dialog_input"><input type="text" id="folder_name" name ="folder_name required"/></div>';
        echo '<input type="hidden" readonly id="folder_path" name ="folder_path" value ='.$path.'/>';
        echo '</br>';
        echo '<button type="submit" id="submit_folder" class="btn btn-success">Add</button>';
        echo '<button type="reset" id="reset_folder" class="btn btn-success pull-right">Cancel</button>';

      Dialog::end(); ?>

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

            <form id="data" method="post" enctype="multipart/form-data" action="">
                <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
                <input type="hidden" readonly id="file_path" name="file_path" value="<?=$path;?>" />
                <div class="form-group">
                    <label>File</label>
                    <input class="form-control" name="file" id="fileUpload" type="file" required>
                </div>
                <div class="form-group">
                    <button type="submit" id="submit_files" class="btn btn-success">Add</button>
                    <button type="reset" id="reset_files" class="btn btn-success pull-right">Cancel</button>
                </div>
            </form>

            <?php Dialog::end(); ?>
