<?php

namespace app\assets;

use yii\web\AssetBundle;
use yii\web\View;

class StatisticsAsset extends AssetBundle
{
    public $js = [
        'https://cdn.amcharts.com/lib/4/core.js',
        'https://cdn.amcharts.com/lib/4/charts.js',
        'https://cdn.amcharts.com/lib/4/maps.js',
        'https://cdn.amcharts.com/lib/4/geodata/worldLow.js',
        'https://cdn.amcharts.com/lib/4/themes/animated.js',
    ];
    public $jsOptions = ['position' => View::POS_HEAD];
}
