<?php

use yii\helpers\Html;
use yii\grid\GridView;
use mdm\admin\components\Helper;

/* @var $this yii\web\View */
/* @var $searchModel mdm\admin\models\searchs\User */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('rbac-admin', 'Users');
$this->params['breadcrumbs'][] = $this->title;
?>
  <div class="user-index">

    <h1>
      <?= Html::encode($this->title) ?>
    </h1>
    <p>
      <?= Html::a('Create Users', ['/site/signup'], ['class'=>'btn btn-success']) ?>
    </p>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'label' => 'First name',
                'attribute' => 'fname',
                'contentOptions' => 
                    [
                        'style' => 'width:200px;'
                    ],
            ],
            [
                'label' => 'Last name',
                'attribute' => 'lname',
                'contentOptions' => 
                    [
                        'style' => 'width:200px;'
                    ],
            ],
            'username',
            'email:email',
            [
               'label' => 'Role',
               'value' => function ($model) {
                   return $model->UserRole($model->id);
               }
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
            [
                'class' => 'yii\grid\ActionColumn',
                'header'=>'Action', 
                'template' => Helper::filterActionColumn(['view', 'activate','deactivate', 'delete']), //'update',
                'contentOptions' => 
                    [
                        'style' => 'width:100px;'
                    ],
                'buttons' => [
                    'activate' => function($url, $model) {
                        if ($model->status == 10) {
                            return " ";
                        }else{
                            $options = [
                            'title' => Yii::t('rbac-admin', 'Activate'),
                            'aria-label' => Yii::t('rbac-admin', 'Activate'),
                            'data-confirm' => Yii::t('rbac-admin', 'Are you sure you want to activate this user?'),
                            'data-method' => 'post',
                            'data-pjax' => '10',
                        ];
                        return Html::a('<span class="glyphicon glyphicon-ok"></span>', $url, $options);
                        }
                    },
                    'deactivate' => function($url, $model) {
                        if ($model->status == 10) {
                            $options = [
                            'title' => Yii::t('rbac-admin', 'Deactivate'),
                            'aria-label' => Yii::t('rbac-admin', 'Deactivate'),
                            'data-confirm' => Yii::t('rbac-admin', 'Are you sure you want to deactivate this user?'),
                            'data-method' => 'post',
                            'data-pjax' => '0',
                        ];
                        return Html::a('<span class="glyphicon glyphicon-remove"></span>', $url, $options);
                        }else{
                            return " ";
                        }
                    }
                  ]
                ],
              [
               'label' => 'Reset',
               'format' => 'raw',
               'value'=>function ($data) {
                return Html::a(Html::encode("Reset"),'?r=site/request-password-reset',['class'=>'btn btn-default']);
                      },
            ],
          
            ],
        ]);
        ?>
  </div>