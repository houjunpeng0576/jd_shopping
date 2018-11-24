<?php
namespace backend\controllers;
use common\models\User;
use Yii;
use yii\db\Exception;
use yii\web\Controller;
use yii\data\Pagination;

class UserController extends Controller{
    public $layout = 'layout1';

    /**
     * 修改密码（邮件）
     * @return string
     */
    public function actionMailChangePassword(){
        $this->layout = false;

        $request = Yii::$app->request;

        $time = $request->get('timestamp');
        $username = $request->get('username');
        $token = $request->get('token');

        $model = new User();
        $model->setScenario(User::SCENARIO_CHANGE_PASS);
        $myToken = $model->createToken($username,$time);

        if($token != $myToken){
            $this->redirect(['user/login']);
            Yii::$app->end();
        }

        if(time() - $time > 300){
            $this->redirect(['user/login']);
            Yii::$app->end();
        }

        $model->username = $username;

        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post();
            if($model->changePass($post)){
                Yii::$app->session->setFlash('info','密码修改成功');
            }
        }

        return $this->render('mail-change-password',compact('model'));
    }

    /**
     * 管理员列表
     * @return string
     */
    public function actionUsers(){
        $model = User::find()->joinWith('profile')->where(['valid' => User::VALID]);
        $count = $model->count();

        $page_size = Yii::$app->params['pageSize']['user'];

        $pager = new Pagination([
            'totalCount' => $count,
            'pageSize' => $page_size,
        ]);

        $users = $model->offset($pager->offset)
            ->limit($pager->limit)
            ->orderBy(['userid' => SORT_DESC])
            ->all();

        return $this->render('users',compact('users','pager'));
    }

    /**
     * 创建用户
     * @return string
     */
    public function actionCreate(){
        $model = new User();
        $model->setScenario(User::SCENARIO_CREATE_MANAGER);

        $request = Yii::$app->request;
        if($request->isPost){
            $post = $request->post();
            if($model->create($post)){
                Yii::$app->session->setFlash('info','添加成功');
            }else{
                Yii::$app->session->setFlash('info','添加失败');
            }
        }

        return $this->render('create',compact('model'));
    }

    /**
     * 删除用户
     */
    public function actionDelete(){
        $id = (int)Yii::$app->request->get('id');
        if(empty($id)){
            throw new \Exception();
        }

        if(User::updateAll(['valid' => User::UN_VALID],'userid = :id',[':id' => $id])){
            Yii::$app->session->setFlash('info','删除成功');
            $this->redirect(['user/users']);
        }
    }

    /**
     * 更改个人邮箱
     * @return string
     */
    public function actionEmailChange(){
        $model = User::find()->select(User::DATAFIELD)->where('username = :user',[':user' => Yii::$app->session['user']['username']])->one();
        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post();
            if($model->change_email($post)){
                Yii::$app->session->setFlash('info','修改成功');
            }
        }

        return $this->render('email-change',compact('model'));
    }

    /**
     * 修改个人密码
     */
    public function actionPasswordChange(){
        $model = User::find()->select(User::DATAFIELD)->where('username = :user',[':user' => Yii::$app->session['user']['username']])->one();
        if(Yii::$app->request->isPost){
            $model->setScenario(User::SCENARIO_CHANGE_PASS);
            $post = Yii::$app->request->post();
            if($model->changePass($post)){
                Yii::$app->session->setFlash('info','修改成功');
            }
        }

        return $this->render('password-change',compact('model'));
    }
}