<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\helpers\FileHelper;
use yii\helpers\Url;
use backend\models\Folders;

/**
 * FoldersController implements the CRUD actions for Folders .
 */
class FoldersController extends Controller
{
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
                        'roles' => ['admin'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['logout','index','error','download','view','create','file-upload','edit','delete'],
                        'roles' => ['moderator'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['logout','index','error','download','view'],
                        'roles' => ['public'],
                    ],
                ],
            ],
        ];
    }


    // folder listing
    public function actionIndex()
    {     
                        
        $path =Yii::getAlias('@backend') . "/../uploads/";
        $dataProvider = array_slice(scandir($path), 2);
                
        $user_id =Yii::$app->user->getId();
        $userRole = array_keys(yii::$app->authManager->getRolesByUser($user_id))[0];
        
        if($userRole == 'admin')
        {
            return $this->render('index', [
                    'dataProvider' => $dataProvider,
                    'path'=>$path,
                ]);
        }else
        {
            foreach ($dataProvider as &$value) 
                $value = $path.$value;

          unset($value);
            
            if($userRole == 'moderator')
            {
                $utilities = Folders::find()
                ->select('utility_name')
                ->where(['user_id' => $user_id])
                ->asArray()
                ->all();
            }
            else
            {
                $utilities = Folders::find()
                ->select('utility_name')
                ->where(['public_access' => 'true'])
                ->asArray()
                ->all();
            }
            
            $utility_names = array_column($utilities, 'utility_name');
            $result = array_intersect($dataProvider, $utility_names);

            $newlist = array();
            foreach ($result as $key => $value)
              $newlist[$key] = substr($value, strrpos($value, '/') + 1);

            return $this->render('index', [
                'dataProvider' => $newlist,
                'path'=>$path,
            ]);
        }

    }

    
    //dynamic folder view
    public function actionView($path)
    {
      
        if(is_dir($path))
        {
            $list = scandir($path);
            $dataProvider = array_slice(scandir($path), 2);
          
            if($dataProvider)
            {
                  /*return $this->render('view', [
                    'dataProvider' => $dataProvider,
                    'path'=>$path,
                ]);*/
              
              $user_id =Yii::$app->user->getId();
              $userRole = array_keys(yii::$app->authManager->getRolesByUser($user_id))[0];
        
              if($userRole == 'admin')
              {
                  return $this->render('view', [
                          'dataProvider' => $dataProvider,
                          'path'=>$path,
                      ]);
              }else
              {
                  foreach ($dataProvider as &$value) 
                      $value = $path.$value;

                unset($value);

                  if($userRole == 'moderator')
                  {
                      $utilities = Folders::find()
                      ->select('utility_name')
                      ->where(['user_id' => $user_id])
                      ->asArray()
                      ->all();
                  }
                  else
                  {
                      $utilities = Folders::find()
                      ->select('utility_name')
                      ->where(['public_access' => 'true'])
                      ->asArray()
                      ->all();
                  }

                  $utility_names = array_column($utilities, 'utility_name');
                  $result = array_intersect($dataProvider, $utility_names);

                  $newlist = array();
                  foreach ($result as $key => $value)
                    $newlist[$key] = substr($value, strrpos($value, '/') + 1);

                  return $this->render('view', [
                      'dataProvider' => $newlist,
                      'path'=>$path,
                  ]);
              }
              
            }
            else
            {
                return $this->render('empty', [
                  'path'=>$path,
                ]);
            }
        }
        else
        {
              return $this->render('empty', [
              'path'=>$path,
            ]);
        }
    }
    
    
    // folder creation
    public function actionCreate()
    {
        
        $folder_name = Yii::$app->request->post('folder_name');
        $pathTrim = rtrim(Yii::$app->request->post('folder_path'),'/');
        $path = $pathTrim . "/". $folder_name;
        if(FileHelper::createDirectory($path, $mode = 0777))
             return $path;
           else
             return 0;
    }
  
    
    // file upload
    public function actionFileUpload()
    {

          $filename = $_FILES['file']['name'];
          $path = $_POST['file_path'];
          $location = $path.$filename;
          if(move_uploaded_file($_FILES['file']['tmp_name'],$location))
              return 1;
          else
              return 0;
    }

    
    // download files    
    public function actionDownload($path) 
    { 

        if (file_exists($path)) {
            return Yii::$app->response->sendFile($path);
        }
    }
    
    
    //rename folders or files
    public function actionEdit()
    {
        $name = Yii::$app->request->post('name');
        $old_name = Yii::$app->request->post('old_title');
        $pathTrim = rtrim(Yii::$app->request->post('path'),'/');
        $path = $pathTrim . "/";
        
        if(is_file($path.$old_name)){
            $extension = pathinfo($path.$old_name, PATHINFO_EXTENSION);
            if(rename($path.$old_name,$path.$name.'.'.$extension))
                return 1;
            else
                return 0;
        }else{
            if(rename($path.$old_name,$path.$name))
                return 1;
            else
                return 0;
        }
        
    }


    //delete folders or files
    public function actionDelete($path)
    {
        if(is_dir($path))
            FileHelper::removeDirectory($path);
        else
            unlink(rtrim($path,'/'));

        return $this->redirect(Yii::$app->request->referrer);

    }
    

    //users given access to folders by admin
    public function actionAssignments(){
        
        $user_id = Yii::$app->request->post('user_id'); 
        $manage_utitlities = Yii::$app->request->post('manage_utitlities'); 
        $public_access = Yii::$app->request->post('public_access'); 
        $utility_path = Yii::$app->request->post('utility_path');
    
        $model = new Folders();
        
        $model->user_id = $user_id;
        $model->utility_name = $utility_path;
        $model->manage_utilities =$manage_utitlities;
        $model->public_access = $public_access;
        
        if($model->save())
            return 1;
        else
            return 0;
    
    }
    


    
    
}