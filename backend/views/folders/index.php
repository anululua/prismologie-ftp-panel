<?php

use yii\helpers\Html;
use yii\jui\Dialog;
use mdm\admin\models\User;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */

$this->title = 'Folders';

$users = User::find()->where('id !='.Yii::$app->user->getId())->all();

$listData=ArrayHelper::map($users,'id','username');

?>
    <div class="folders-index">

        <h1>
            <?= Html::encode($this->title) ?>
        </h1>
        <div class="col-md-9">

            <table class="table table-striped">
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
                <tr>
                    <td>
                        <?php echo Html::a($data); ?>
                    </td>
                    <td>
                        <?php if(is_dir($path.$data)){
    
    echo Html::a('<i class="glyphicon glyphicon-eye-open"></i>',['view','path'=>$path.$data.'/'], ['class' => 'btn btn-black', 'title' => 'View']);
}else{
    
    echo Html::a('<i class="glyphicon glyphicon-download-alt"></i>',['download','path'=>$path.$data], ['class' => 'btn btn-black', 'title' => 'Download']);
}
                                             
    echo Html::a('<i class="glyphicon glyphicon-trash"></i>',['delete','path'=>$path.$data.'/'], ['class' => 'btn btn-black', 'title' => 'Delete']);
      
?>
                        <a title="Edit" data-toggle="modal" class="open-dialog btn" data-target="#SettingsModal" data-title="<?=$data;?>" data-path="<?=$path;?>">
              <i class="glyphicon glyphicon-pencil"></i>
            </a>
                    </td>
                    <td>
                        <form id="assignments" name="assignments[<?=$data;?>]" action="javascript:handleClick()">
                            <?=Html::dropDownList('user_list', null,$listData,['prompt' => '--- select user ---','id'=>'user_list','name'=>'user_list'], array('label' => 'Users'));?>
                                <input type="hidden" readonly value="<?=$path.$data;?>" />
                                <span class="pull-right">
                            <label class="checkbox-inline" title="Add/Edit/Delete Utilities">
                                <input type="checkbox" value="1" id="chk" name = "chk[]">Manage Utilities
                            </label>
                            <label class="checkbox-inline" title="View/Download Utilities">
                                <input type="checkbox" value="2" id="check" name="check[]" checked="checked">Public Access
                            </label>
                            <button type="submit" id="user_assignment" class="btn btn-default btn-xs">Save</button>
                        </span>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
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
                    <input type="text" id="name" required />
                </div>
                <div class="modal-footer">
                    <button type="submit" id="edit_name" class="btn btn-success pull-left">Change</button>
                    <button type="reset" class="btn btn-success" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <script type="application/javascript">
        function handleClick() {
            alert(23);

            user_id = $("input[name='user_list']:").val();
            if (user_id)
                alert(user_id);
            else
                alert('no user_id');
            //return false;
            event.preventDefault();

        }

    </script>
