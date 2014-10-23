<?php
use yii\helpers\Html;

/**
 * @var $this yii\web\View
 * @var $products \common\models\Product[]
 */
$this->title = 'Мои товары';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= Html::a('Добавить товар', 'create', ['class' => 'btn btn-success'])?>
    <?= \yii\grid\GridView::widget([
        'dataProvider' => $products,
        'emptyText' => 'Нет товаров',
        'columns' => [
            [
                'class' => \yii\grid\DataColumn::className(),
                'value' => function(\common\models\Product $model) {return $model->getImage();},
                'format' => 'image',
                'label' => 'Изображение',
            ],
            [
                'class' => \yii\grid\DataColumn::className(),
                'attribute' => 'name',
                'format' => 'text',
            ],
            [
                'class' => \yii\grid\DataColumn::className(),
                'attribute' => 'created_at',
                'format' => ['date', 'php:Y-m-d'],
            ],
            [
                'class' => \yii\grid\ActionColumn::className(),
                'template' => '{update} {delete}',
                'header' => 'Действия'
            ]
        ]
    ]) ?>
</div>