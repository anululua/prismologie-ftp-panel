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
         
    echo Html::a('<i class="glyphicon glyphicon-pencil"></i>','#',['class'=>'btn btn-black','title' => 'Edit','id'=>'edit_title', 'title_name'=>$data,]);                    
                        
    echo Html::a('<i class="glyphicon glyphicon-trash"></i>',['delete','path'=>$path.$data.'/'], ['class' => 'btn btn-black', 'title' => 'Delete']);
      
?>
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

    <?php
        
            Dialog::begin([
            'id'=>'editdialog',
            'clientOptions' => [
            'modal' => true,
            'title'=>"New name",
            'autoOpen'=>false,
            'dialogClass'=>'no-close',
          ],
        ]); 
        
        echo '<div class="dialog_input"><input type="text" id="name" name ="name required"/></div>';
        echo '<input type="hidden" readonly id="path" name ="path" value ='.$path.'/>';
        echo '<input type="hidden" id="title" name ="title" value = ""/>';
        echo '</br>';
        echo '<button type="submit" id="edit_name" class="btn btn-success">Add</button>';
        echo '<button type="reset" id="reset" class="btn btn-success pull-right">Cancel</button>';

      Dialog::end(); ?>
