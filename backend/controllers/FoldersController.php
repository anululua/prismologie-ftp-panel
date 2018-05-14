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
                    'delete' => ['POST'],
                    'file-upload' => ['POST'],
                ],
            ],
        ];
    }


    public function actionIndex()
    {        
        $path =Yii::getAlias('@backend') . "/../uploads/";
        $dataProvider = array_slice(scandir($path), 2);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionView($path)
    {
      echo $path;
      die();
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
    

    public function actionCreate()
    {
        
        $folder_name = Yii::$app->request->post('folder_name');
        $path = Yii::getAlias('@backend') . "/../uploads/". $folder_name;
        if(FileHelper::createDirectory($path, $mode = 0777))
             return 1;
           else
             return false;
    }
  
  public function actionFileUpload()
    {
      return Yii::$app->request->post('file_data');
    die();
      //$file_name = Yii::$app->request->post('folder_name');

      $this->file->saveAs('uploads/' . Yii::$app->request->post());

        return 1;
    }


    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $categoryOld= $model->name;
        if ($model->load(Yii::$app->request->post())) {
            
            $src = Yii::getAlias('@backend') . "/web/uploads/". $categoryOld;
            
            $categoryNew = Yii::$app->request->post('Categories')['name'];
            $dest = Yii::getAlias('@backend') . "/web/uploads/". $categoryNew;
            FileHelper::createDirectory($dest, $mode = 0777);
                
            FileHelper::copyDirectory($src,$dest);
                if($model->save()){
                    FileHelper::removeDirectory($src);
                    return $this->redirect(['view', 'id' => $model->id]);
                }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }


    public function actionDelete($id)
    {
        $categoryName= $this->findModel($id)->name;
        $path = Yii::getAlias('@backend') . "/web/uploads/". $categoryName;
        FileHelper::removeDirectory($path);

        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Categories::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}