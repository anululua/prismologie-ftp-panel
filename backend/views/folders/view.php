<?php

use yii\helpers\Html;
use mdm\admin\models\User;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */

$this->title = 'Folders';

$users = User::find()->where('id !='.Yii::$app->user->getId())->all();

$listData=ArrayHelper::map($users,'id','username');

?>
    <div class="folders-view">

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
    echo Html::a('<i class="glyphicon glyphicon-pencil"></i>',['edit','path'=>$path.$data.'/'], ['class' => 'btn btn-black', 'title' => 'Edit']);
    echo Html::a('<i class="glyphicon glyphicon-trash"></i>',['delete','path'=>$path.$data.'/'], ['class' => 'btn btn-black', 'title' => 'Delete']);
                        
?>


                    </td>
                    <td>
                        <form>
                            <?=Html::dropDownList('user_list', null,$listData,['prompt' => '--- select user ---','id'=>'user_list'], array('label' => 'Users'));?>

                                <span class="pull-right">
                            <label class="checkbox-inline" title="Add/Edit/Delete Utilities">
                                <input type="checkbox" value="1" id="chk">Manage Utilities
                            </label>
                            <label class="checkbox-inline" title="View/Download Utilities">
                                <input type="checkbox" value="2" id="check" checked="checked">Public Access
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
