<?php
namespace frontend\controllers;

use yii\web\Controller;

class LoginController extends Controller{
    //商场首页
    public function actionIndex(){
        return $this->renderPartial('index');
    }
}