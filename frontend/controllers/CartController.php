<?php
namespace frontend\controllers;

use yii\web\Controller;

class CartController extends Controller{
    //购物车页面
    public function actionIndex(){
        return $this->renderPartial('index');
    }
}