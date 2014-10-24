<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$form = ActiveForm::begin(['id' => 'form-signup']); ?>

<?= $form->field($model, 'username') ?>

<?= $form->field($model, 'email') ?>

<?= $form->field($model, 'password')->passwordInput() ?>

<?= $form->field($model, 'password_repeat')->passwordInput() ?>

<div class="form-group">
    <?= Html::submitButton($model->getScenario() == 'signup' ? 'Регистрация' : 'Сохранить', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
</div>

<?php ActiveForm::end(); ?>
 