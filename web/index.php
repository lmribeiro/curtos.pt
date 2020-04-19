<?php

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__.'/../vendor/autoload.php';
require __DIR__.'/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__.'/../config/web.php';


\Yii::$container->set('yii\grid\GridView', [
    'emptyText' => '-',
    'tableOptions' => [
        'class' => 'table table-striped table-hover',
    ],
    'layout' => "{items}\n
        <div class=\"row\">
            <div class=\"col-sm-12 col-md-5 pl-4\">
                <div class=\"dataTables_info\" id=\"multi-filter-select_info\">
                    {summary}
                </div>
            </div>

            <div class=\"col-sm-12 col-md-7\">
                <div class=\"dataTables_paginate  float-right\" id=\"multi-filter-select_paginate\">
                    {pager}
                </div>
            </div>
        </div>",
    'pager' => [
        'options' => [
            'class' => 'pagination',
        ],
        'linkOptions' => ['class' => 'page-link'],
        'pageCssClass' => 'paginate_button page-item',
        'prevPageCssClass' => 'paginate_button page-item previous',
        'prevPageLabel' => Yii::t('app', 'Anterior'),
        'nextPageCssClass' => 'paginate_button page-item next',
        'nextPageLabel' => Yii::t('app', 'Seguinte'),
        'activePageCssClass' => 'paginate_button page-item active',
        'disabledPageCssClass' => 'paginate_button page-item disabled',
    ],
]);

\Yii::$container->set('yii\widgets\DetailView', [
    'options' => ['class' => 'table table-striped detail-view'],
    'template' => '<tr style="margin:0px" class="row"><td class="col-sm-3"{captionOptions}>{label}</td><td class="col-sm-9"{contentOptions}>{value}</td></tr>',
]);

(new yii\web\Application($config))->run();
