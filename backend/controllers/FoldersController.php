<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
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
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    //'delete' => ['POST'],
                ],
            ],
        ];
    }


    // folder listing
    public function actionIndex()
    {     
        
        $path =Yii::getAlias('@backend') . "/../uploads/";
        $dataProvider = array_slice(scandir($path), 2);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'path'=>$path,
        ]);
    }

    
    //dynamic folder view
    public function actionView($path)
    {

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
        if(rename($path.$old_name,$path.$name))
            return 1;
        else
            return 0;
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
