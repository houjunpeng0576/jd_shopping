<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\RunoobTbl */

$this->title = 'Update Runoob Tbl: ' . $model->runoob_id;
$this->params['breadcrumbs'][] = ['label' => 'Runoob Tbls', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->runoob_id, 'url' => ['view', 'id' => $model->runoob_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="runoob-tbl-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
