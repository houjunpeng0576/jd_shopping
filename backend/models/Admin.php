<?php
namespace backend\models;

use common\models\BaseModel;
use Yii;

class Admin extends BaseModel
{
    const SCENARIO_LOGIN = 'login';//登录
    const SCENARIO_SEEK_PASS = 'seek_pass';//邮箱重置密码
    const SCENARIO_CHANGE_PASS = 'change_pass';//修改密码
    const SCENARIO_CREATE_MANAGER = 'create_manager';//创建管理员
    const SCENARIO_CHANGE_EMAIL = 'change_mail';//修改邮箱

    public $rememberMe = true;
    public $rePass;

    public static function tableName()
    {
        return "{{%admin}}";
    }

    public function rules()
    {
        return [
            ['adminuser','required','message' => '用户名不能为空','on' => [self::SCENARIO_LOGIN,self::SCENARIO_SEEK_PASS,self::SCENARIO_CREATE_MANAGER,self::SCENARIO_CHANGE_EMAIL]],
            ['adminpass','required','message' => '密码不能为空','on' => [self::SCENARIO_LOGIN,self::SCENARIO_CHANGE_PASS,self::SCENARIO_CREATE_MANAGER,self::SCENARIO_CHANGE_EMAIL]],
            ['adminuser','unique','message' => '用户名被已注册','on' => self::SCENARIO_CREATE_MANAGER],
            ['rememberMe','boolean','on' => self::SCENARIO_LOGIN],
            ['adminpass','validatePass','on' => [self::SCENARIO_LOGIN,self::SCENARIO_CHANGE_EMAIL]],
            ['adminemail','required','message' => '邮箱不能为空','on' => [self::SCENARIO_SEEK_PASS,self::SCENARIO_CREATE_MANAGER,self::SCENARIO_CHANGE_EMAIL]],
            ['adminemail','email','message' => '邮箱格式错误','on' => [self::SCENARIO_SEEK_PASS,self::SCENARIO_CREATE_MANAGER,self::SCENARIO_CHANGE_EMAIL]],
            ['adminemail','validateEmail','on' => self::SCENARIO_SEEK_PASS],
            ['adminemail','unique','message' => '邮箱已被注册','on' => [self::SCENARIO_CREATE_MANAGER,self::SCENARIO_CHANGE_EMAIL]],
            ['rePass','required','message' => '确认密码不能为空','on' => [self::SCENARIO_CHANGE_PASS,self::SCENARIO_CREATE_MANAGER]],
            ['rePass','compare','compareAttribute' => 'adminpass','message' => '两次密码输入不一致','on' => [self::SCENARIO_CHANGE_PASS,self::SCENARIO_CREATE_MANAGER]],
        ];
    }

    public function scenarios()
    {
        return [
            self::SCENARIO_LOGIN => ['adminuser','adminpass','rememberMe'],
            self::SCENARIO_SEEK_PASS => ['adminuser','adminemail'],
            self::SCENARIO_CHANGE_PASS => ['adminpass','rePass'],
            self::SCENARIO_CREATE_MANAGER => ['adminuser','adminemail','adminpass','rePass'],
            self::SCENARIO_CHANGE_EMAIL => ['adminuser','adminemail','adminpass'],
        ];
    }

    /**
     * 验证密码
     */
    public function validatePass(){
        if(!$this->hasErrors()){
            $data = self::find()->where('adminuser = :user AND adminpass = :pass',[':user' => $this->adminuser, ':pass' => md5($this->adminpass)])->one();
            if(is_null($data)){
                $this->addError('adminpass','用户名或密码错误');
            }
        }
    }

    public function validateEmail(){
        $data = self::find()->where('adminuser = :user AND adminemail = :email',[':user' => $this->adminuser, ':email' => $this->adminemail])->one();
        if(is_null($data)){
            $this->addError('adminemail','管理员邮箱不匹配');
        }
    }

    public function attributeLabels()
    {
        return [
            'adminid' => 'ID',
            'adminuser' => '管理员账号',
            'adminpass' => '管理员密码',
            'adminemail' => '管理员邮箱',
            'logintime' => '最后登陆时间',
            'loginip' => '最后登陆IP',
            'createtime' => '创建时间',
            'valid' => '是否失效',
            'rePass' => '确认密码',
        ];
    }

    /**
     * 登录
     * @param $data
     * @return bool
     */
    public function login($data){
        if($this->load($data) && $this->validate()){
            $lifetime = $this->rememberMe ? 24 * 3600 * 3 : 0;
            $session = Yii::$app->session;
            $session->setCookieParams([$lifetime]);
            $session['admin'] = [
                'adminUser' => $this->adminuser,
                'isLogin' => 1,
            ];

            //更新登陆时间和ip
            self::updateAll(['logintime' => time(), 'loginip' => ip2long(Yii::$app->request->userIP)],'adminuser = :user',[':user' => $this->adminuser]);

            return (bool)$session['admin']['isLogin'];
        }

        return false;
    }

    /**
     * 邮箱忘记密码
     * @param $data
     */
    public function seekPass($data){
        if($this->load($data) && $this->validate()){
            $time = time();
            $token = $this->createToken($this->adminuser,$time);
            $mailer = Yii::$app->mailer->compose('email-seek-password',[
                'adminuser' => $this->adminuser,
                'time' => $time,
                'token' => $token
            ]);
            $mailer->setFrom('13522141884@163.com');
            $mailer->setTo($this->adminemail);
            $mailer->setSubject('找回密码');

            if($mailer->send()){
                return true;
            }
        }

        return false;
    }

    /**
     * 创建令牌
     * @param $adminuser
     * @param $time
     * @return string
     */
    public function createToken($adminuser,$time){
        return md5(md5($adminuser).base64_encode(Yii::$app->request->userIP).md5($time).md5(Yii::$app->params['token_halt']));
    }

    public function changePass($data){
        if($this->load($data) && $this->validate()){
            try{
                return (bool)self::updateAll(['adminpass' => md5($this->adminpass)],'adminuser = :user',[':user' => $this->adminuser]);
            }catch (Exception $e){
                echo 33;die;
                //TODO:记录日志
            }
        }

        return false;
    }

    /**
     * 创建用户
     * @param $data
     * @return bool
     */
    public function create($data){
        if($this->load($data) && $this->validate()){
            $this->adminpass = md5($this->adminpass);
            $this->valid = self::VALID;
            $this->createtime = time();
            //save(false)：取消验证，save默认带验证
            if($this->save(false)){
                return true;
            }
        }

        return false;
    }

    /**
     * 修改邮箱
     */
    public function change_email($data){
        $this->setScenario(self::SCENARIO_CHANGE_EMAIL);
        if($this->load($data) && $this->validate()){
            return (bool)self::updateAll(['adminemail' => $this->adminemail],'adminuser = :user',[':user' => $this->adminuser]);
        }

        return false;
    }
}