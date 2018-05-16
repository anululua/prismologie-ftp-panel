<?php

use yii\helpers\Html;
use yii\jui\Dialog;

/* @var $this yii\web\View */

$this->title = 'Folders';
$this->params['breadcrumbs'][] = $this->title;



?>
    <div class="folders-index">

        <h1>
            <?= Html::encode($this->title) ?>
        </h1>
        <div class="col-md-9">
            <table class="table table-striped">
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
                        <?php echo $this->render('//layouts/assignments');?>
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
