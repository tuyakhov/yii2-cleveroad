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

        if ($model->load(\Yii::$app->getRequest()->getBodyParams(), '') && $model->login()) {
            echo \Yii::$app->user->identity->getAccessToken();
        } else {
            return $model;
        }
    }

    public function actionEditProfile()
    {
        $model = new UserForm(['user' => User::findIdentity(\Yii::$app->getUser()->id)]);
        if ($model->load(\Yii::$app->getRequest()->getBodyParams(), '')) {
            if ($model->editProfile())
                return ['success' => true, 'profile' => $model];
            else
                return $model;
        }
        return new BadRequestHttpException('There are no parameters given');
    }
}
 