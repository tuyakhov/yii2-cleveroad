<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Product */

$this->title = 'Редактирование товара : ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Товары', 'url' => ['my-products']];
$this->params['breadcrumbs'][] = $model->name . ' редактирование';
?>
<div class="product-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>