<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'ds/dist/css/front.css',
        'css/custom.css',
        'css/night.css',
    ];
    public $js = [
//        'js/jquery.min.js',
        'js/popper.min.js',
        'js/bootstrap.min.js',
        'js/bootstrap-notify.js',
        'js/script.js'
    ];
//    public $depends = ['yii\web\YiiAsset', 'yii\bootstrap\BootstrapPluginAsset'];

}
