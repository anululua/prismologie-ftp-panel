<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\helpers\FileHelper;
use yii\helpers\Url;
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
                        'actions' => ['logout','index','error'],
                        'roles' => ['@'],
                    ],
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
        
        $roles = Yii::$app->authmanager->getRoles(); 
                
        $path =Yii::getAlias('@backend') . "/../uploads/";
        $dataProvider = array_slice(scandir($path), 2);
        
        //$userRole = array_keys(yii::$app->authManager->getRolesByUser(Yii::$app->user->getId()))[0];
        
        
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'path'=>$path,
        ]);
    }

    
    //dynamic folder view
    public function actionView($path)
    {
       /* if (Yii::$app->user->can('downloadFiles')){ echo 'permission granted'; } else{ echo 'no permission'; } exit;*/
        if(is_dir($path)){
            $list = scandir($path);
            $dataProvider = array_slice(scandir($path), 2);
            if($dataProvider){
                return $this->render('view', [
                'dataProvider' => $dataProvider,
                'path'=>$path,
            ]);
          }else{
            return $this->render('empty', [
            'path'=>$path,
        ]);
        }
        }else{
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

    
}
