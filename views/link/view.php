<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\YiiAsset;
use yii\widgets\DetailView;


/* @var $this yii\web\View */
/* @var $model app\models\Link */
/* @var array $browsers */
/* @var array $countrys */


$this->title = Yii::t('app', 'Curto') . " " . $model->short;
$link = Url::base() . "/" . $model->short;
YiiAsset::register($this);
?>

<div class="section  mt-5">
    <div class="container">
        <div class="page-header">
            <div class="row">
                <div class="col-md-8 mx-auto text-center mb-2">
                    <h1 class="display-3"><?= $this->title ?></h1>
                    <p class="lead">
                        <a href="<?= $link ?>" target="new">
                            <?= $link ?>
                        </a>
                    </p>
                </div>
            </div>
        </div>
        <div class="row">

            <div class="col-lg-8 col-xs-12">

                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        [
                            'attribute' => 'user_id',
                            'format' => 'raw',
                            'visible' => Yii::$app->user->identity->role == 'ADMIN',
                            'value' => $model->user ? $model->user->username : null,
                        ],
                        [
                            'attribute' => 'short',
                            'label' => Yii::t('app', 'Curto'),
                            'format' => 'raw',
                            'value' => Html::a($link, $link, ['target' => 'new']),
                        ],
                        [
                            'attribute' => 'target',
                            'format' => 'raw',
                            'value' => Html::a($model->target, $model->target, ['target' => 'new']),
                        ],
//                        'visit_count',
                        'expires_after',
                        'created_at',
                        'updated_at',
                    ],
                ]) ?>
            </div>

            <div class="col-lg-4 col-xs-12">
                <div class="card bg-light mb-3">
                    <div class="card-body text-center">
                        <i class="fas fa-users fa-5x"></i>
                        <h4 class="card-title"><?= $model->visit_count ?></h4>
                        <p class="card-text text-uppercase"><?= Yii::t('app', 'Visitas'); ?></p>
                    </div>
                </div>
            </div>

        </div>

        <?= $this->render('_partials/browsers', [
            'data' => $browsers,
        ]) ?>

        <?= $this->render('_partials/countrys', [
            'data' => $countrys,
        ]) ?>


    </div>
</div>

