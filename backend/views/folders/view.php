<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'Folders';
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="folders-index">

        <h1>
            <?= Html::encode($this->title) ?>
        </h1>

        <div class="col-md-5">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Options</th>
                    </tr>
                </thead>

                <?php foreach ($dataProvider as $data): ?>
                <tr>
                    <td>
                        <?php echo Html::a($data); ?>
                    </td>
                    <td>
                        <?= Html::a('<i class="glyphicon glyphicon-eye-open"></i>',['view'], ['class' => 'btn btn-black', 'title' => 'View']);?>
                            <?= Html::a('<i class="glyphicon glyphicon-pencil"></i>',['edit'], ['class' => 'btn btn-black', 'title' => 'Edit']);?>
                                <?= Html::a('<i class="glyphicon glyphicon-trash"></i>',['delete'], ['class' => 'btn btn-black', 'title' => 'Delete']); ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <div class="col-md-6 col-md-offset-1">

            <?php echo $this->render('//layouts/utility');?>

        </div>

    </div>
