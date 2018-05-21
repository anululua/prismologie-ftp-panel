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
        <tr id="<?=$data;?>">
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

            <?=Html::dropDownList('user_list', null,$listData,['prompt' => '--- select user ---','id'=>'user_list','name'=>'user_list'], array('label' => 'Users'));?>


              <!--
<input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />-->
              <input type="hidden" name="path" value="<?=$path.$data;?>" />

              <input type="checkbox" title="Add/Edit/Delete Utilities" value="1" id="chk" name="chk" class="checkbox_check">Manage Utilities

              <input type="checkbox" title="View/Download Utilities" value="2" id="check" name="check" checked="checked">Public Access

              <button type="button" id="user_assignment" name="user_assignment" class="btn btn-default btn-xs" onclick="submitRowAsForm('<?=$data;?>')">Save</button>

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


  <script>
    function submitRowAsForm(idRow) {

      alert(('"#' + idRow + ' td"'));

      var user_id, utility_path, public_access, manage_utitlities;

      $("#" + idRow + " td").children().each(function() {
        // alert(23);

        if (this.type.substring(0, 6) == "select") {
          user_id = this.value;
        } else if (this.type.substring(0, 6) == "hidden") {
          if (this.name == 'path') {
            utility_path = this.value;
          }
        } else if (this.type.substring(0, 8) == "checkbox") {

          if (this.name == 'chk') {
            if ($(this).prop('checked')) {
              manage_utitlities = true;

              if (!user_id) {
                $("#user_list").css({
                  "border": "1px solid red",
                  "background": "#FFCECE"
                });
                return false;
              } else {
                ajaxUserAssignment(user_id, manage_utitlities, public_access);
              }

            } else {
              manage_utitlities = null;
              ajaxUserAssignment(user_id, manage_utitlities, public_access);

            }
          } else if (this.name == 'check') {
            if ($(this).prop('checked'))
              public_access = true;
            else
              public_access = null;
          }
        }

        //event.preventDefault();
      });


    }

    function ajaxUserAssignment(user_id, manage_utitlities, public_access) {

      $.ajax({
        url: '?r=folders/assignments',
        type: 'POST',
        data: {
          user_id: user_id,
          manage_utitlities: manage_utitlities,
          public_access: public_access
        },
        success: function(data) {
          //alert(data);
          location.reload();
          if (data) {

          } else {

          }
        }
      });

      return false;
    }
  </script>