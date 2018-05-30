<?php

use yii\helpers\Html;
use yii\jui\Dialog;
use mdm\admin\models\User;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */

$this->title = 'Folders';
$current_user = Yii::$app->user->getId();
$users = User::find()->where('id !='.$current_user)->all();
$listData=ArrayHelper::map($users,'id','username');

?>
    <div class="folders-view">

        <h1>
            <?= Html::encode($this->title) ?>
        </h1>

        <div class="col-md-9 table-responsive">
            <input type="hidden" readonly value="<?=$val;?>" id="user_assignment_access" />
            <?php if(!empty($dataProvider)){?>
            <table class="table table-striped " name="utility_table" id="utility_table">
                <colgroup>
                    <col span="1" style="width: 20%;">
                    <col span="1" style="width: 20%;">
                    <col span="1" style="width: 60%;">
                </colgroup>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Options</th>
                        <th>Assignments</th>
                    </tr>
                </thead>

                <?php foreach ($dataProvider as $data): ?>
                <?php $pieces = explode(".", $data);?>
                <tr id="<?=$pieces[0];?>">
                    <td>
                        <?php echo Html::a($data); ?>
                    </td>
                    <td>
                        <?php if(is_dir($path.$data)){
  
    echo Html::a('<i class="glyphicon glyphicon-eye-open"></i>',['view','path'=>$path.$data.'/','val'=>$val], ['class' => 'btn btn-black', 'title' => 'View']);
}else{
  
    echo Html::a('<i class="glyphicon glyphicon-download-alt"></i>',['download','path'=>$path.$data], ['class' => 'btn btn-black', 'title' => 'Download']);
}
    echo Html::a('<i class="glyphicon glyphicon-trash"></i>',['delete','path'=>$path.$data.'/'], ['class' => 'btn btn-black', 'title' => 'Delete','id' => 'del_utility']);
                        
?>
                        <a title="Edit" data-toggle="modal" id="edit_utility" class="open-dialog btn" data-target="#SettingsModal" data-title="<?=$data;?>" data-path="<?=$path;?>">
              <i class="glyphicon glyphicon-pencil"></i>
            </a>

                    </td>
                    <td>
                        <?php if(is_dir($path.$data)){?>
                        <?=Html::dropDownList('user_list', null,$listData,['prompt' => '--- select user ---','id'=>'user_list','name'=>'user_list'], array('label' => 'Users'));?>

                            <input type="hidden" name="path" value="<?=$path.$data;?>" />

                            <input type="checkbox" title="Add/Edit/Delete Utilities" value="1" id="chk" name="chk" class="checkbox_check">Manage Utilities

                            <input type="checkbox" title="View/Download Utilities" value="2" id="check" name="check">Public Access

                            <button type="button" id="user_assignment" name="user_assignment" class="btn btn-default btn-xs" onclick="submitRowAsForm('<?=$pieces[0];?>')">Save</button>
                            <?php } else
                            echo '<center>-</center>';
                            ?>
                    </td>
                </tr>

                <?php endforeach; ?>
            </table>
            <?php } else 
                echo "Empty Folder";
            ?>
        </div>

        <?php echo $this->render('//layouts/utility',['path'=>$path]);?>

    </div>

    <div class="modal fade" id="SettingsModal" tabindex="-1" role="dialog" aria-labelledby="SettingsModalLabel">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"> 
                  <span aria-hidden="true">&times;</span>
                </button>
                    <h4 class="modal-title">Add new name</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" value="" id="path" name="path" readonly/>
                    <input type="hidden" value="" id="old_title" name="old_title" readonly/>
                    <input type="text" id="name" required autofocus/>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="edit_name" class="btn btn-success pull-left">Change</button>
                    <button type="reset" class="btn btn-success" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
