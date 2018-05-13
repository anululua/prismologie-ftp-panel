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


    
    public function actionPopup()
{
		
	$this->renderPartial('view',array('data'=>'Ur-data'),false,true);
		
}
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }


    public function actionCreate()
    {
        $model = new Categories();

        if ($model->load(Yii::$app->request->post())) {
            
            $categoryName = Yii::$app->request->post('Categories')['name'];
            $path = Yii::getAlias('@backend') . "/web/uploads/". $categoryName;
            
            if (FileHelper::createDirectory($path, $mode = 0777)) {
                if($model->save()){
                return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
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
