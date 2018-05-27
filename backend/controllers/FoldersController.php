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

    
    
    public function actionArchives(){
        
        $path =Yii::getAlias('@backend') . "/../archives/";
        $dataProvider = array_slice(scandir($path), 2);

        return $this->render('archives', [
              'dataProvider'=>$dataProvider,
              'path'=>$path,
            ]);
        
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
      

        $list = scandir($path);
        $dataProvider = array_slice(scandir($path), 2);

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
    
    
    // folder creation
    public function actionCreate()
    {
        
        $folder_name = Yii::$app->request->post('folder_name');
        $pathTrim = rtrim(Yii::$app->request->post('folder_path'),'/');
        $path = $pathTrim . "/". $folder_name;
        if(FileHelper::createDirectory($path, $mode = 0777))
        {
            Yii::$app->session->setFlash('success', 'Successfully created folder');
            return $path;
        }
        else
        {
            Yii::$app->session->setFlash('error', 'Failed to create folder');
            return 0;
        }
    }
  
    
    // file upload
    public function actionFileUpload()
    {

        $j= 0;
        $file_count = count($_FILES['file']['name']);
        
       // return $_FILES['form-data'];
        for($i=0; $i<$file_count; $i++)
        {
            if(is_uploaded_file($_FILES['file']['tmp_name'][$i]))
            {
                $sourcePath = $_FILES['file']['tmp_name'][$i];
                $targetPath = $_POST['file_path'].$_FILES['file']['name'][$i];
                
                if(move_uploaded_file($sourcePath, $targetPath)=== true) 
                    $j++;
            }
        }

        if($j == $file_count){
            Yii::$app->session->setFlash('success', 'Successfully uploaded file');
            return 1;
        }
        else{
            Yii::$app->session->setFlash('error', 'Failed to upload file');
            return 0;
        } 
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
        
        if(is_file($path.$old_name))
        {
            $extension = pathinfo($path.$old_name, PATHINFO_EXTENSION);
            if(rename($path.$old_name,$path.$name.'.'.$extension))
            {       
                Yii::$app->session->setFlash('success', 'Successfully edited file name');
                return 1;
            }            
            else
            {              
                Yii::$app->session->setFlash('error', 'Failed to edit file');
                return 0;
            }        
        }
        else
        {
            if(rename($path.$old_name,$path.$name))
            {                
                Yii::$app->session->setFlash('success', 'Successfully edited folder name');
                return 1;
            }            
            else
            {                
                Yii::$app->session->setFlash('error', 'Failed to edit folder name');
                return 0;   
            }        
        }
        
    }


        //archives folders or files
        public function actionDelete($path)
        {
            $dst =Yii::getAlias('@backend') . "/../archives/";
            $name = basename($path);

            if (is_dir ( $path )) 
            {
                $return = FileHelper::copyDirectory($path, $dst.$name);
                FileHelper::removeDirectory($path);
                Yii::$app->session->setFlash('success', 'Successfully archived folder'); 
            } 
            else if (file_exists ( rtrim($path,'/') ))
            {

                copy(rtrim($path,'/'), $dst.$name);
                unlink ( rtrim($path,'/') );
                Yii::$app->session->setFlash('success', 'Successfully archived file'); 
            }
            return $this->redirect(['index']);
        }
    
    
     //delete folders or files permanently from the archives folder
        public function actionArchiveDelete($path)
        {
            if(is_dir($path))
            {
                FileHelper::removeDirectory($path);
                Yii::$app->session->setFlash('success', 'Successfully deleted folder');
            }
            else
            {
                unlink(rtrim($path,'/'));
                Yii::$app->session->setFlash('success', 'Successfully deleted file');
            }
            return $this->redirect(['archives']);
        }


    //users given access to folders by admin
    public function actionAssignments(){
        
        $user_id = Yii::$app->request->post('user_id')?Yii::$app->request->post('user_id'):null; 
        $manage_utitlities = Yii::$app->request->post('manage_utitlities'); 
        $public_access = Yii::$app->request->post('public_access'); 
        $utility_path = Yii::$app->request->post('utility_path');
    
        $mod =  Folders::findOne(['user_id' => $user_id, 'utility_name' => $utility_path, 'manage_utilities' => $manage_utitlities,'public_access'=>$public_access]);
        
        if ($mod == null) 
            $model = new Folders();
         else 
            $model = $mod;
                
        $model->user_id = $user_id;
        $model->utility_name = $utility_path;
        $model->manage_utilities =$manage_utitlities;
        $model->public_access = $public_access;
        
        if ($mod == null) 
            ($model->save())? Yii::$app->session->setFlash('success', 'Assigment successfull'): Yii::$app->session->setFlash('error', 'Assigment failed');
         else 
            ($model->update())? Yii::$app->session->setFlash('success', 'Assigment successfully updated'): Yii::$app->session->setFlash('error', 'Assigment updation failed');
    }
    
    
    
}
