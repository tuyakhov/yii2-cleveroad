<?php
namespace api\controllers;

use common\models\LoginForm;
use common\models\User;
use frontend\models\UserForm;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\rest\ActiveController;
use yii\web\BadRequestHttpException;
use yii\web\ServerErrorHttpException;

class UserController extends ActiveController
{
    public $modelClass = 'common\models\User';

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'view' => [
                'class' => 'yii\rest\ViewAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'options' => [
                'class' => 'yii\rest\OptionsAction',
            ],
        ];
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
            'only' => ['edit-profile']
        ];
        return $behaviors;
    }

    public function actionLogin()
    {
        $model = new LoginForm();
        if ($model->load(\Yii::$app->getRequest()->getBodyParams(), '') && $model->login())
            return \Yii::$app->user->identity->getAccessToken();
        return $model;
    }

    public function actionEditProfile()
    {
        $model = new UserForm(['user' => User::findIdentity(\Yii::$app->getUser()->id)]);
        if (!$model->load(\Yii::$app->getRequest()->getBodyParams(), '') && !$model->editProfile())
            throw new ServerErrorHttpException('Failed to update the object for unknown reason.');
        return $model;
    }
}
 