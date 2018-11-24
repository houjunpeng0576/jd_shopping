<?php
use yii\bootstrap\ActiveForm;//帮助创建from组件
use yii\helpers\Html;//创建按钮
$this->title = '忘记密码'
?>
<!DOCTYPE html>
<html class="login-bg">
<head>
    <title>慕课商城 - <?= $this->title?></title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- bootstrap -->
    <link href="/css/bootstrap/bootstrap.css" rel="stylesheet" />
    <link href="/css/bootstrap/bootstrap-responsive.css" rel="stylesheet" />
    <link href="/css/bootstrap/bootstrap-overrides.css" type="text/css" rel="stylesheet" />

    <!-- global styles -->
    <link rel="stylesheet" type="text/css" href="/css/layout.css" />
    <link rel="stylesheet" type="text/css" href="/css/elements.css" />
    <link rel="stylesheet" type="text/css" href="/css/icons.css" />

    <!-- libraries -->
    <link rel="stylesheet" type="text/css" href="/css/lib/font-awesome.css" />

    <!-- this page specific styles -->
    <link rel="stylesheet" href="/css/compiled/signin.css" type="text/css" media="screen" />

    <!-- open sans font -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css' />

    <!--[if lt IE 9]>
    <script src="/js/html5.js"></script>
    <![endif]-->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>
<body>


<div class="row-fluid login-wrapper">
    <a class="brand" href="index.html"></a>
    <?php $from = ActiveForm::begin([
        "fieldConfig" => [
            "template" => '{error}{input}'
        ]
    ])?>
    <div class="span4 box">
        <div class="content-wrap">
            <h6>慕课商城 - <?= $this->title?></h6>
            <?= Yii::$app->session->hasFlash('info') ? Yii::$app->session->getFlash('info') : ''; ?>
            <?= $from->field($model,'adminuser')->textInput(["class" => "span12", "placeholder" => "管理员账号"])?>
            <?= $from->field($model,'adminemail')->textInput(["class" => "span12", "placeholder" => "管理员邮箱"])?>
            <a href="<?= \yii\helpers\Url::to(['public/login'])?>" class="forgot">返回登陆</a>
            <?= Html::submitButton('找回密码',["class" => "btn-glow primary login"])?>
        </div>
    </div>
    <?php $from = ActiveForm::end()?>
    <div class="span4 no-account">
        <p>没有账户?</p>
        <a href="signup.html">注册</a>
    </div>
</div>

<!-- scripts -->
<script src="/js/jquery-latest.js"></script>
<script src="/js/bootstrap.min.js"></script>
<script src="/js/theme.js"></script>

<!-- pre load bg imgs -->
<script type="text/javascript">
    $(function () {
        // bg switcher
        var $btns = $(".bg-switch .bg");
        $btns.click(function (e) {
            e.preventDefault();
            $btns.removeClass("active");
            $(this).addClass("active");
            var bg = $(this).data("img");

            $("html").css("background-image", "url('img/bgs/" + bg + "')");
        });

    });
</script>

</body>
</html>