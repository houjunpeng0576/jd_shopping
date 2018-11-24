<?php
namespace frontend\controllers;

use yii\web\Controller;

class IndexController extends Controller{
    //商场首页
    public function actionIndex(){
        return $this->renderPartial('index');
    }
}