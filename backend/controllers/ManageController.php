<?php
namespace backend\controllers;
use backend\models\Admin;
use Yii;
use yii\web\Controller;
use yii\data\Pagination;

class ManageController extends Controller{
    public $layout = 'layout1';

    const DATAFIELD = ['adminid','adminuser','adminemail','logintime','loginip','createtime'];

    /**
     * 修改密码（邮件）
     * @return string
     */
    public function actionMailChangePassword(){
        $this->layout = false;

        $request = Yii::$app->request;

        $time = $request->get('timestamp');
        $adminuser = $request->get('adminuser');
        $token = $request->get('token');

        $model = new Admin();
        $model->setScenario(Admin::SCENARIO_CHANGE_PASS);
        $myToken = $model->createToken($adminuser,$time);

        if($token != $myToken){
            $this->redirect(['public/login']);
            Yii::$app->end();
        }

        if(time() - $time > 300){
            $this->redirect(['public/login']);
            Yii::$app->end();
        }

        $model->adminuser = $adminuser;

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
    public function actionManagers(){
        $model = Admin::find()->where(['valid' => Admin::VALID]);
        $count = $model->count();

        $page_size = Yii::$app->params['pageSize']['manager'];

        $pager = new Pagination([
            'totalCount' => $count,
            'pageSize' => $page_size,
            ]);

        $managers = $model->select(self::DATAFIELD)
            ->offset($pager->offset)
            ->limit($pager->limit)
            ->orderBy(['adminid' => SORT_DESC])
            ->all();

        return $this->render('managers',compact('managers','pager'));
    }

    /**
     * 创建用户
     * @return string
     */
    public function actionCreate(){
        $model = new Admin();
        $model->setScenario(Admin::SCENARIO_CREATE_MANAGER);

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
            $this->redirect(['manage/managers']);
        }

        if(Admin::updateAll(['valid' => Admin::UN_VALID],'adminid = :id',[':id' => $id])){
            Yii::$app->session->setFlash('info','删除成功');
            $this->redirect(['manage/managers']);
        }
    }

    /**
     * 更改个人邮箱
     * @return string
     */
    public function actionEmailChange(){
        $model = Admin::find()->select(self::DATAFIELD)->where('adminuser = :user',[':user' => Yii::$app->session['admin']['adminUser']])->one();
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
        $model = Admin::find()->select(self::DATAFIELD)->where('adminuser = :user',[':user' => Yii::$app->session['admin']['adminUser']])->one();
        if(Yii::$app->request->isPost){
            $model->setScenario(Admin::SCENARIO_CHANGE_PASS);
            $post = Yii::$app->request->post();
            if($model->changePass($post)){
                Yii::$app->session->setFlash('info','修改成功');
            }
        }

        return $this->render('password-change',compact('model'));
    }
}