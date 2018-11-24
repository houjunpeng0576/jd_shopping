<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = '创建用户';
?>
<link rel="stylesheet" href="/css/compiled/new-user.css" type="text/css" media="screen" />
<!-- main container -->
<div class="content">

    <div class="container-fluid">
        <div id="pad-wrapper" class="new-user">
            <div class="row-fluid header">
                <h3>添加新用户</h3>
            </div>

            <div class="row-fluid form-wrapper">
                <!-- left column -->
                <div class="span9 with-sidebar">
                    <div class="container">
                        <?php $from = ActiveForm::begin([
                            "fieldConfig" => [
                                "template" => '{error}<div class="span12 field-box">{label}{input}</div>'
                            ],
                            "options" => [
                                "class" => "new_user_form inline-input"
                            ]
                        ])?>
                        <?= Yii::$app->session->hasFlash('info') ? Yii::$app->session->getFlash('info') : ''?>
                        <?= $from->field($model,'username')->textInput(['class' => 'span9'])?>
                        <?= $from->field($model,'useremail')->textInput(['class' => 'span9'])?>
                        <?= $from->field($model,'userpass')->passwordInput(['class' => 'span9'])?>
                        <?= $from->field($model,'rePass')->passwordInput(['class' => 'span9'])?>

                        <div class="span11 field-box actions">
                            <?= Html::submitButton('创建',['class' => 'btn-glow primary'])?>
                            <span>或者</span>
                            <input type="reset" value="取消" class="reset" />
                        </div>
                        <?php $from = ActiveForm::end()?>
                    </div>
                </div>

                <!-- side right column -->
                <div class="span3 form-sidebar pull-right">

                    <div class="alert alert-info hidden-tablet">
                        <i class="icon-lightbulb pull-left"></i>
                        请在左侧填写用户相关信息，包括用户账号，电子邮箱，以及密码
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end main container -->
