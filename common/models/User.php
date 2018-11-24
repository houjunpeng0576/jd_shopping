<?php
namespace common\models;

use common\models\BaseModel;
use Yii;

class User extends BaseModel
{
    const SCENARIO_LOGIN = 'login';//登录
    const SCENARIO_SEEK_PASS = 'seek_pass';//邮箱重置密码
    const SCENARIO_CHANGE_PASS = 'change_pass';//修改密码
    const SCENARIO_CREATE_MANAGER = 'create_manager';//创建管理员
    const SCENARIO_CHANGE_EMAIL = 'change_mail';//修改邮箱

    const DATAFIELD = ['userid','username','useremail','logintime','loginip','createtime','realname','birthday','age'];

    public $rememberMe = true;
    public $rePass;

    public static function tableName()
    {
        return "{{%user}}";
    }

    public function rules()
    {
        return [
            ['username','required','message' => '用户名不能为空','on' => [self::SCENARIO_LOGIN,self::SCENARIO_SEEK_PASS,self::SCENARIO_CREATE_MANAGER,self::SCENARIO_CHANGE_EMAIL]],
            ['userpass','required','message' => '密码不能为空','on' => [self::SCENARIO_LOGIN,self::SCENARIO_CHANGE_PASS,self::SCENARIO_CREATE_MANAGER,self::SCENARIO_CHANGE_EMAIL]],
            ['username','unique','message' => '用户名被已注册','on' => self::SCENARIO_CREATE_MANAGER],
            ['rememberMe','boolean','on' => self::SCENARIO_LOGIN],
            ['userpass','validatePass','on' => [self::SCENARIO_LOGIN,self::SCENARIO_CHANGE_EMAIL]],
            ['useremail','required','message' => '邮箱不能为空','on' => [self::SCENARIO_SEEK_PASS,self::SCENARIO_CREATE_MANAGER,self::SCENARIO_CHANGE_EMAIL]],
            ['useremail','email','message' => '邮箱格式错误','on' => [self::SCENARIO_SEEK_PASS,self::SCENARIO_CREATE_MANAGER,self::SCENARIO_CHANGE_EMAIL]],
            ['useremail','validateEmail','on' => self::SCENARIO_SEEK_PASS],
            ['useremail','unique','message' => '邮箱已被注册','on' => [self::SCENARIO_CREATE_MANAGER,self::SCENARIO_CHANGE_EMAIL]],
            ['rePass','required','message' => '确认密码不能为空','on' => [self::SCENARIO_CHANGE_PASS,self::SCENARIO_CREATE_MANAGER]],
            ['rePass','compare','compareAttribute' => 'userpass','message' => '两次密码输入不一致','on' => [self::SCENARIO_CHANGE_PASS,self::SCENARIO_CREATE_MANAGER]],
        ];
    }

    public function scenarios()
    {
        return [
            self::SCENARIO_LOGIN => ['username','userpass','rememberMe'],
            self::SCENARIO_SEEK_PASS => ['username','useremail'],
            self::SCENARIO_CHANGE_PASS => ['userpass','rePass'],
            self::SCENARIO_CREATE_MANAGER => ['username','useremail','userpass','rePass'],
            self::SCENARIO_CHANGE_EMAIL => ['username','useremail','userpass'],
        ];
    }

    /**
     * 验证密码
     */
    public function validatePass(){
        if(!$this->hasErrors()){
            $data = self::find()->where('username = :user AND userpass = :pass',[':user' => $this->username, ':pass' => md5($this->userpass)])->one();
            if(is_null($data)){
                $this->addError('userpass','用户名或密码错误');
            }
        }
    }

    public function validateEmail(){
        $data = self::find()->where('username = :user AND useremail = :email',[':user' => $this->username, ':email' => $this->useremail])->one();
        if(is_null($data)){
            $this->addError('useremail','用户邮箱不匹配');
        }
    }

    public function attributeLabels()
    {
        return [
            'userid' => 'ID',
            'username' => '用户账号',
            'userpass' => '用户密码',
            'useremail' => '用户邮箱',
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
            $session['user'] = [
                'username' => $this->username,
                'isLogin' => 1,
            ];

            //更新登陆时间和ip
            self::updateAll(['logintime' => time(), 'loginip' => ip2long(Yii::$app->request->userIP)],'username = :user',[':user' => $this->username]);

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
            $token = $this->createToken($this->username,$time);
            $mailer = Yii::$app->mailer->compose('email-seek-password',[
                'username' => $this->username,
                'time' => $time,
                'token' => $token
            ]);
            $mailer->setFrom('13522141884@163.com');
            $mailer->setTo($this->useremail);
            $mailer->setSubject('找回密码');

            if($mailer->send()){
                return true;
            }
        }

        return false;
    }

    /**
     * 创建令牌
     * @param $username
     * @param $time
     * @return string
     */
    public function createToken($username,$time){
        return md5(md5($username).base64_encode(Yii::$app->request->userIP).md5($time).md5(Yii::$app->params['token_halt']));
    }

    public function changePass($data){
        if($this->load($data) && $this->validate()){
            try{
                return (bool)self::updateAll(['userpass' => md5($this->userpass)],'username = :user',[':user' => $this->username]);
            }catch (\Exception $e){
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
            $this->userpass = md5($this->userpass);
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
            return (bool)self::updateAll(['useremail' => $this->useremail],'username = :user',[':user' => $this->username]);
        }

        return false;
    }

    public function getProfile(){
        return $this->hasOne(UserProfile::className(),['userid' => 'userid']);
    }
}