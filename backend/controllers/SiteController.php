<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use common\models\LoginForm;
use backend\models\PasswordResetRequestForm;
use backend\models\ResetPasswordForm;
use backend\models\SignupForm;
use backend\models\ChangePassword;


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
             return $this->render('index');

        }else{
            $model = new LoginForm();
            if ($model->load(Yii::$app->request->post()) && $model->login()) {
                 return $this->render('index');
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
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->render('index');
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->render('index');
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
    
    
    
    /**
     * Reset password
     * @return string
     */
    public function actionChangePassword($user_id)
    {
        $model = new ChangePassword();
        if ($model->load(Yii::$app->getRequest()->post()) && $model->change($user_id)) {
            return $this->render('index');
        }

        return $this->render('change-password', [
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
        return $this->redirect(['login']);
    }
    
    
}
