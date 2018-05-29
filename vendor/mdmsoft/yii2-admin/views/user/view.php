<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use mdm\admin\components\Helper;

/* @var $this yii\web\View */
/* @var $model mdm\admin\models\User */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rbac-admin', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$controllerId = $this->context->uniqueId . '/';
?>
    <div class="user-view">

        <h1>
            <?= Html::encode($this->title) ?>
        </h1>

        <p>
            <?php
        if ($model->status == 0 && Helper::checkRoute($controllerId . 'activate')) {
            echo Html::a(Yii::t('rbac-admin', 'Activate'), ['activate', 'id' => $model->id], [
                'class' => 'btn btn-primary',
                'data' => [
                    'confirm' => Yii::t('rbac-admin', 'Are you sure you want to activate this user?'),
                    'method' => 'post',
                ],
            ]);
        }
        ?>
                <?php
        if (Helper::checkRoute($controllerId . 'delete')) {
            echo Html::a(Yii::t('rbac-admin', 'Delete'), ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                    'method' => 'post',
                ],
            ]);
        }
        ?>
        </p>

        <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'label' => 'First name',
                'attribute' => 'fname',
            ],
            'username',
            'email:email',
            'created_at:date',
            [ 
                'attribute' => 'status', 
                'value' => function($model) 
                { 
                    return $model->status == 0 ? 'Inactive' : 'Active'; 
                }, 
            ],
        ],
    ])
    ?>
            <div class="users_folders">
                <?= GridView::widget(['dataProvider' => $provider]);?>
            </div>
    </div>
