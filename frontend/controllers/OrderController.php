<?php
namespace frontend\controllers;

use yii\web\Controller;

class OrderController extends Controller{
    public function actionIndex(){
        return $this->renderPartial('index');
    }

    //收银台页面
    public function actionCheck(){
        return $this->renderPartial('check');
    }
}