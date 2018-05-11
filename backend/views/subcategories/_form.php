<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\models\Categories;

/* @var $this yii\web\View */
/* @var $model backend\models\Subcategories */
/* @var $form yii\widgets\ActiveForm */
?>

    <div class="subcategories-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

            <?php
        $categories=Categories::find()->all();
        $listData=ArrayHelper::map($categories,'id','name');
        echo $form->field($model, 'cat_id')->dropDownList($listData, ['prompt'=>'Choose category']);
    ?>


                <div class="form-group">
                    <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
                </div>

                <?php ActiveForm::end(); ?>

    </div>
