<?php
use yii\helpers\Url;

$this->title = '管理员列表';
?>
<div class="content">

    <div class="container-fluid">
        <div id="pad-wrapper" class="users-list">
            <div class="row-fluid header">
                <h3>管理员列表</h3>
                <div class="span10 pull-right">
                    <input type="text" class="span5 search" placeholder="Type a user's name..." />

                    <!-- custom popup filter -->
                    <!-- styles are located in css/elements.css -->
                    <!-- script that enables this dropdown is located in js/theme.js -->
                    <div class="ui-dropdown">
                        <div class="head" data-toggle="tooltip" title="Click me!">
                            查找
                            <i class="arrow-down"></i>
                        </div>
                        <div class="dialog">
                            <div class="pointer">
                                <div class="arrow"></div>
                                <div class="arrow_border"></div>
                            </div>
                            <div class="body">
                                <p class="title">
                                    条件：
                                </p>
                                <div class="form">
                                    <select>
                                        <option />用户名
                                        <option />邮箱
                                        <option />ID
                                        <option />已登录
                                        <option />最后登录
                                    </select>
                                    <select>
                                        <option />等于
                                        <option />不等于
                                        <option />大于
                                        <option />开始
                                        <option />包含
                                    </select>
                                    <input type="text" />
                                    <a class="btn-flat small">添加条件</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <a href="<?= Url::to(['manage/create'])?>" class="btn-flat success pull-right">
                        <span>&#43;</span>
                        新用户
                    </a>
                </div>
            </div>

            <!-- Users table -->
            <div class="row-fluid table">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th class="span3 sortable">
                            管理员ID
                        </th>
                        <th class="span3 sortable">
                            <span class="line"></span>管理员账号
                        </th>
                        <th class="span3 sortable">
                            <span class="line"></span>邮箱
                        </th>
                        <th class="span3 sortable">
                            <span class="line"></span>最后登陆时间
                        </th>
                        <th class="span3 sortable">
                            <span class="line"></span>最后登陆IP
                        </th>
                        <th class="span3 sortable">
                            <span class="line"></span>添加时间
                        </th>
                        <th class="span3 sortable">
                            <span class="line"></span>操作
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($managers as $manager){ ?>
                        <tr>
                            <td>
                                <?= $manager->adminid?>
                            </td>
                            <td>
                                <?= $manager->adminuser?>
                            </td>
                            <td>
                                <a href="#"><?= $manager->adminemail?></a>
                            </td>
                            <td>
                                <?= date('Y-m-d H:i:s',$manager->logintime)?>
                            </td>
                            <td>
                                <?= long2ip($manager->loginip)?>
                            </td>
                            <td>
                                <?= date('Y-m-d H:i:s',$manager->createtime)?>
                            </td>
                            <td>
                                <a href="<?= Url::to(['manage/delete','id' => $manager->adminid])?>">删除</a>
                            </td>
                        </tr>
                    <?php }?>
                    </tbody>
                </table>
                <?= Yii::$app->session->hasFlash('info') ? Yii::$app->session->getFlash('info'): ''?>
            </div>

            <div class="pagination pull-right">
                <?php
                echo \yii\widgets\LinkPager::widget([
                    'pagination' => $pager,
                    'prevPageLabel' => '&#8249;',
                    'nextPageLabel' => '&#8250;',
                ])
                ?>
            </div>
            <!-- end users table -->
        </div>
    </div>
</div>
