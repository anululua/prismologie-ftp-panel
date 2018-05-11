<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\searches\ArticlesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Articles';
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="articles-index">

        <h1>
            <?= Html::encode($this->title) ?>
        </h1>
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

        <p>
            <?= Html::a('Create Articles', ['create'], ['class' => 'btn btn-success']) ?>
        </p>

        <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'name',
            'filename',
            'type',
            'uploads_path',
            [
            'header' => 'Category',
            'attribute' => 'subcat.name',
            ],
            [
                'attribute' => 'status',
                'value' => function($model) {
                    return $model->status == 0 ? 'Inactive' : 'Active';
                },
                'filter' => [
                    0 => 'Inactive',
                    10 => 'Active'
                ]
            ],
            'created_at:date',
            //'updated_at',

            ['class' => 'yii\grid\ActionColumn',
            'header'=>'Action',
            ],
        ],
    ]); ?>
    </div>
