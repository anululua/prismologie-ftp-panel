<?php

namespace mdm\admin\controllers;

use Yii;
use mdm\admin\models\User;
use mdm\admin\models\searchs\User as UserSearch;
use mdm\admin\models\form\ChangePassword;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\base\UserException;
use yii\mail\BaseMailer;

use yii\data\ArrayDataProvider;
use yii\helpers\FileHelper;
use backend\models\Folders;

use yii\filters\AccessControl;


/**
 * User controller
 */
class UserController extends Controller
{
    private $_oldMailPath;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        /*return [ 'verbs' => [ 'class' => VerbFilter::className(), 'actions' => [ 'delete' => ['post'], 'logout' => ['post'], 'activate' => ['post'], 'deactivate' => ['post'], ], ], ];*/
        
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            if (Yii::$app->has('mailer') && ($mailer = Yii::$app->getMailer()) instanceof BaseMailer) {
                /* @var $mailer BaseMailer */
                $this->_oldMailPath = $mailer->getViewPath();
                $mailer->setViewPath('@mdm/admin/mail');
            }
            return true;
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function afterAction($action, $result)
    {
        if ($this->_oldMailPath !== null) {
            Yii::$app->getMailer()->setViewPath($this->_oldMailPath);
        }
        return parent::afterAction($action, $result);
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $file_url=[];
        $path= Yii::getAlias('@webroot').'/uploads/';
        $user_id =Yii::$app->user->getId();
        
        if(!empty(yii::$app->authManager->getRolesByUser($id)))
            $userRole = array_keys(yii::$app->authManager->getRolesByUser($id))[0];            
        
        $directories = FileHelper::findDirectories($path, ['recursive' => true]);
        
        if(!isset($userRole)){
            $file_url = [];
        }
        else if($userRole == 'admin')
        {
            foreach($directories as $value)
                $file_url[]["name"]= str_replace(Yii::getAlias('@webroot/uploads/\\'), '', $value);
        }
        else 
        {

            if($userRole == 'moderator')
            {
                $utilities = Folders::find()
                ->select('utility_name')
                ->where(['user_id' => $id])
                ->asArray()
                ->all();
            }
             if($userRole == 'public')
            {
                $utilities = Folders::find()
                ->select('utility_name')
                ->where(['public_access' => 'true','user_id' => $id])
                ->asArray()
                ->all();
            }
        
            $utility_names = array_column($utilities, 'utility_name');
            
            foreach($utility_names as $value)
                $file_url[]["name"] = str_replace(Yii::getAlias('@webroot/uploads/'), '', $value);
            
        }    
        
        $provider = new ArrayDataProvider([
            'allModels' => $file_url,
            'pagination' => [
                'pageSize' => 5,
            ],
        ]);
        
        return $this->render('view', [
                'model' => $this->findModel($id),
                'provider' => $provider
            ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if($id ==1){
            Yii::$app->session->setFlash('error', 'cannot delete super admin');
            return $this->redirect(['index']);
        }
        $this->findModel($id)->delete();
        Folders::deleteAll('user_id = :user_id', [':user_id' => $id]);
        Yii::$app->session->setFlash('success', 'Successfully deleted user');
        return $this->redirect(['index']);
    }

    /**
     * Activate new user
     * @param integer $id
     * @return type
     * @throws UserException
     * @throws NotFoundHttpException
     */
    public function actionActivate($id)
    {
        /* @var $user User */
        $user = $this->findModel($id);
        if ($user->status == User::STATUS_INACTIVE) {
            $user->status = User::STATUS_ACTIVE;
            if ($user->save()) {
                Yii::$app->session->setFlash('success', 'Successfully activated user');
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('success', 'Error!! please try again');
                $errors = $user->firstErrors;
                throw new UserException(reset($errors));
            }
        }
        return $this->redirect(['index']);
    }

    
    /**
     * Deactivate new user
     * @param integer $id
     * @return type
     * @throws UserException
     * @throws NotFoundHttpException
     */
    public function actionDeactivate($id)
    {
        if($id ==1){
            Yii::$app->session->setFlash('error', 'cannot deactivate super admin');
            return $this->redirect(['index']);
        }
        /* @var $user User */
        $user = $this->findModel($id);
        if ($user->status == User::STATUS_ACTIVE) {
            
            $user->status = User::STATUS_INACTIVE;
            if ($user->save()) {
                Yii::$app->session->setFlash('success', 'Successfully deactivated user');
                $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('success', 'Error!! please try again');
                $errors = $user->firstErrors;
                throw new UserException(reset($errors));
            }
        }
        return $this->redirect(['index']);
    }
    
    /**
     * Reset password
     * @return string
     */
    public function actionChangePassword($user_id)
    {
        $model = new ChangePassword();
        if ($model->load(Yii::$app->getRequest()->post()) && $model->change($user_id)) {
            Yii::$app->session->setFlash('success', 'New password saved');
            return $this->redirect(['index']);
        }
        return $this->render('change-password', [
                'model' => $model,
        ]);
    }
    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
