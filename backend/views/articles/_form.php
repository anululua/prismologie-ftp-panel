<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\models\Subcategories;
/* @var $this yii\web\View */
/* @var $model backend\models\Articles */
/* @var $form yii\widgets\ActiveForm */
?>

    <div class="articles-form">

        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'file')->fileInput() ?>

                <?php
        $subcategories=subcategories::find()->all();
        $listData=ArrayHelper::map($subcategories,'id','name');
        echo $form->field($model, 'subcat_id')->dropDownList($listData, ['prompt'=>'Choose subcategory']);
    ?>
                    <div class="form-group">
                        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>

    </div>
