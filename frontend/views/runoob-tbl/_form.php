<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\RunoobTbl */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="runoob-tbl-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'runoob_title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'runoob_author')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'submission_date')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
