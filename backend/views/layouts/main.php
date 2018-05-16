<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

AppAsset::register($this);
?>
    <?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">

    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
            <title>
                <?= Html::encode($this->title) ?>
            </title>
            <?php $this->head() ?>
    </head>

    <body>
        <?php $this->beginBody() ?>

        <div class="container-fluid">

            <div class="row wrapper">

                <nav id="sidebar">
                    <div class="sidebar-header">
                        <h3>Prismologie</h3>
                    </div>
                    <?php
                    if (!Yii::$app->user->isGuest){?>
                        <ul class="list-unstyled components">
                            <li class="active">
                                <?= Html::a('Folders', ['//folders']) ?>
                            </li>
                            <li>
                                <?php 
                                $userRole = array_keys(yii::$app->authManager->getRolesByUser(Yii::$app->user->getId()))[0];
                                if($userRole === 'admin')     
                                    echo Html::a('Users', ['//admin/user']);
                                ?>
                            </li>

                        </ul>
                        <?php }?>
                </nav>

                <!-- Page Content Holder -->
                <div id="content" class="container">

                    <nav class="navbar navbar-default">
                        <div class="navbar-header">
                            <button type="button" id="sidebarCollapse" class="btn btn-info navbar-btn">
                                <i class="glyphicon glyphicon-align-left"></i>
                            </button>
                        </div>

                        <?php
                    if (!Yii::$app->user->isGuest){?>
                            <div class="pull-right">
                                <?= Html::a('Logout (' . Yii::$app->user->identity->username . ')', ['site/logout'], ['class' => 'btn btn-link logout'], ['data' => ['method' => 'post']]); ?>

                            </div>
                            <?php }?>
                    </nav>
                    <?= Alert::widget(); ?>
                        <?= $content; ?>

                </div>

            </div>

        </div>


        <?php $this->endBody() ?>
    </body>

    </html>
    <?php $this->endPage() ?>
