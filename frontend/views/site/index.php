<?php
/* @var $this yii\web\View */
$this->title = 'My Yii Application';
?>
<div class="site-index" ng-app="Cleveroad">
    <div class="row">
        <div class="col-md-4 col-md-offset-4" ng-controller="LoginCtrl">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Авторизация
                </div>
                <div class="panel-body">
                    <div class="input-group form-group">
                        <span class="input-group-addon">@</span>
                        <input type="text" class="form-control" placeholder="Email" required ng-model="credentials.email">
                    </div>
                    <div class="input-group form-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                        <input type="text" class="form-control" placeholder="Password" required ng-model="credentials.password">
                    </div>
                    <div class="form-group"><input type="checkbox" ng-model="remember"/> Remember me</div>
                    <a class="btn btn-success" href="#" ng-click="login()"><i class="glyphicon glyphicon-log-in"></i>&nbsp;Войти</a>
                </div>
            </div>
        </div>
    </div>
</div>
