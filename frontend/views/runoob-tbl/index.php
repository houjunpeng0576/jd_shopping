<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\RunoobTblSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Runoob Tbls';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="runoob-tbl-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Runoob Tbl', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'runoob_id',
            'runoob_title',
            'runoob_author',
            'submission_date',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
