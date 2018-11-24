<?php
namespace frontend\controllers;

use common\models\User;
use yii\web\Controller;

class RegisterController extends Controller{
    //商场首页
    public function actionIndex(){
        $model = new User();
        return $this->renderPartial('index',compact('model'));
    }
}