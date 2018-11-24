<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\RunoobTbl */

$this->title = 'Create Runoob Tbl';
$this->params['breadcrumbs'][] = ['label' => 'Runoob Tbls', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="runoob-tbl-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
