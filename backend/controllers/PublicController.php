<?php
namespace backend\controllers;

use backend\models\Admin;
use yii\web\Controller;
use Yii;

class PublicController extends Controller
{
    public $layout = false;

    public function actionLogin(){
        $model = new Admin();
        $model->setScenario(Admin::SCENARIO_LOGIN);
        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post();
            if($model->login($post)){
                $this->redirect(['site/index']);
                Yii::$app->end();
            }
        }

        return $this->render('login',compact('model'));
    }

    public function actionLogout(){
        $session = Yii::$app->session;
        $session->removeAll();
        if(!isset($session['admin']['isLogin'])){
            $this->redirect(['public/login']);
            Yii::$app->end();
        }

        $this->goBack();
    }

    public function actionSeekPassword(){
        $model = new Admin();
        $model->setScenario(Admin::SCENARIO_SEEK_PASS);
        $request = Yii::$app->request;
        if($request->isPost){
            $post = $request->post();
            if($model->seekPass($post)){
                Yii::$app->session->setFlash('info','邮件已成功发送，请查收');
            }
        }

        return $this->render('seek-password',compact('model'));
    }
}