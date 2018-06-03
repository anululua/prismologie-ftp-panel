<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use common\models\LoginForm;
use backend\models\PasswordResetRequestForm;
use backend\models\ResetPasswordForm;
use backend\models\SignupForm;


/**
 * Site controller
 */
class SiteController extends Controller
{
    
    public $defaultAction = 'login';
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {    
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['login', 'index','error'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['logout','index','error'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['logout','index','error'],
                        'roles' => ['moderator'],
                    ],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {        
        return $this->render('index');
    }
    

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
             return $this->redirect(['index']);

        }else{
            $model = new LoginForm();
            if ($model->load(Yii::$app->request->post()) && $model->login()) {
                 return $this->redirect(['index']);
        } else {
            $model->password = '';
            return $this->render('login', [
                'model' => $model,
            ]);
            }
        }
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()) {
                    Yii::$app->session->setFlash('success', 'Sorry, we are unable to reset password for the provided email address.');
                    Yii::$app->response->redirect(array('//admin/assignment/view','id'=>$user->id));
                }
            }
        }
        return $this->render('signup', [
            'model' => $model,
        ]);
    }
    
    
    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->redirect(['site/']);
    }
    
    
}
