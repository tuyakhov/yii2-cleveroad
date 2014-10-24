<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \common\models\User */

$this->title = 'Редактирование профиля';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Пожалуйста заполните поля для регистрации:</p>

    <div class="row">
        <div class="col-lg-5">
            <?= $this->render('_userForm', ['model' => $model])?>
        </div>
    </div>
</div>
