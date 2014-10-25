<?php
namespace frontend\assets;

use yii\web\AssetBundle;

class AngularAsset extends AssetBundle
{
    public $sourcePath = '@bower';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
        'angular/angular.js',
        'angular-route/angular-route.js',
    ];
    public $depends = [

    ];
}
