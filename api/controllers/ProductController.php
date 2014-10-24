<?php
namespace api\controllers;

use common\models\Product;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

class ProductController extends ActiveController
{
    public $modelClass = 'common\models\Product';

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['update'], $actions['create']);
        return $actions;
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::className(),
            'authMethods' => [
                HttpBasicAuth::className(),
                HttpBearerAuth::className(),
                QueryParamAuth::className(),
            ],
            'except' => ['index', 'view']
        ];
        return $behaviors;
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if (!$model->load(\Yii::$app->request->getBodyParams(), '') && !$model->save()) {
            throw new ServerErrorHttpException('Failed to update the object for unknown reason.');
        }
        return $model;
    }

    public function actionCreate()
    {
        $model = new Product;
        if (!$model->load(\Yii::$app->request->getBodyParams(), '') && !$model->save()) {
            throw new ServerErrorHttpException('Failed to update the object for unknown reason.');
        }
        return $model;
    }

    private function findModel($id) {
        $model = Product::find()->self()->where(['id' => $id])->one();
        if (empty($model))
            throw new NotFoundHttpException('Продукт не найден');
        return $model;
    }

}