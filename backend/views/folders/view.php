<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Categories */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Folders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="folders-view">

        <h1>
            <?= Html::encode($this->title) ?>
        </h1>

        <p>
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        </p>

        <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            'name',
            [ 
                'attribute' => 'status', 
                'value' => function($model) 
                { 
                    return $model->status == 0 ? 'Inactive' : 'Active'; 
                }, 
            ],
            'created_at:date',
            'updated_at:date',
        ],
    ]) ?>

    </div>
