<?php
namespace frontend\controllers;

use yii\web\Controller;

class ProductController extends Controller{
    //商品页面
    public function actionIndex(){
        return $this->renderPartial('index');
    }

    //商品详情页
    public function actionDetail(){
        return $this->renderPartial('detail');
    }
}