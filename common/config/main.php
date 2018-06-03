<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
        'modules' => [
        'auth' => [
            'class' => 'common\modules\auth\Module',
        ],
        'admin' => [
            'class' => 'mdm\admin\Module',
            //'layout' => 'left-menu',
        ],
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
    ],
    
    'as access' => [
        'class' => 'mdm\admin\components\AccessControl',
        'allowActions' => [
            'site/*',
            'admin/assignment/*',
            'admin/user/*',
            'folder/*',

        ]
    ],
];
