<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
$this->title = 'Список товаров';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <div class="row">
        <h1><?= Html::encode($this->title) ?></h1>
        <?= \yii\widgets\ListView::widget([
            'dataProvider' => $products,
            'itemOptions' => ['class' => 'item'],
            'itemView' => '_product',
            'emptyText' => 'Нет товаров'
        ]) ?>
    </div>
</div>