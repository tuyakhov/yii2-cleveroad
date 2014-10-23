<?php
namespace frontend\controllers;

use common\models\Product;
use Yii;
use yii\base\InvalidParamException;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * Site controller
 */
class ProductController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['my-products', 'create', 'update'],
                'rules' => [
                    [
                        'actions' => ['my-products', 'create', 'update'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Shows all products in board
     * @return mixed
     */
    public function actionList()
    {
        $products = new ActiveDataProvider([
            'query' => Product::find(),
            'pagination' => [
                'pageSize' => 10
            ]
        ]);
        return $this->render('list', ['products' => $products]);
    }

    /**
     * Shows grid of user products
     * @return mixed
     */
    public function actionMyProducts()
    {
        $products = new ActiveDataProvider([
            'query' => Product::find()->self(),
            'pagination' => [
                'pageSize' => 10
            ]
        ]);
        return $this->render('myProducts', ['products' => $products]);
    }

    /**
     * Creates a new Product model.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Product();

        if ($model->load(Yii::$app->request->post()) && $model->uploadImage() && $model->save()) {
            Yii::$app->getSession()->setFlash('success', 'Товар успешно создан');
            return $this->redirect(['my-products']);
        }
        return $this->render('create', ['model' => $model]);
    }

    /**
     * Updates an existing Product model.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->uploadImage() && $model->save()) {
                Yii::$app->getSession()->setFlash('success', 'Товар успешно изменен');
                return $this->redirect(['my-products']);
        }
        return $this->render('update', ['model' => $model]);
    }

    /**
     * Deletes an existing Product model.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['my-products']);
    }

    /**
     * Finds the Product model based on its primary key value that owns to current user.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Product::find()->self()->where(['id' => $id])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Товар не найден');
        }
    }
}