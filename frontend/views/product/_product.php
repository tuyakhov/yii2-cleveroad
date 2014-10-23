<?php
/**
 * @var $model \common\models\Product
 */
?>
<div class="col-sm-5 col-md-3">
    <div class="thumbnail">
        <img class="img-circle" src="<?= \yii\helpers\Url::to($model->getImage()) ?>" alt="...">
        <div class="caption text-center">
            <h3><?= \yii\helpers\Html::encode($model->name) ?></h3>
            <p class="badge"><?= \yii\helpers\Html::encode($model->getPrice()) ?></p>
            <p>
                <small><?= \yii\helpers\Html::encode($model->owner->email) ?></small>
            </p>
            <p>
                <small class="text-success"><?= Yii::$app->formatter->asDate($model->created_at) ?></small>
            </p>
        </div>
    </div>
</div>
