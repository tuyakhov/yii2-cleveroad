<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/confirm-registration', 'auth' => $user->auth_key]);
?>

Здравствуйте <?= Html::encode($user->username) ?>,

Пожалуйста перейдите по ссылке для подтверждения регистрации:

<?= Html::a(Html::encode($resetLink), $resetLink) ?>
