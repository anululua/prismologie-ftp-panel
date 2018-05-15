<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'Folders';
$this->params['breadcrumbs'][] = $this->title;



?>
    <div class="folders-view">

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
    echo Html::a('<i class="glyphicon glyphicon-pencil"></i>',['edit','path'=>$path.$data.'/'], ['class' => 'btn btn-black', 'title' => 'Edit']);
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
