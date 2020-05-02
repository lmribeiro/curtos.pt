<?php
/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Modals;
use yii\helpers\Html;
use yii\helpers\Url;
use app\assets\AppAsset;

AppAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <?php $this->head() ?>

        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="apple-touch-icon" sizes="57x57" href="<?= Url::to('@web/img/favicon/apple-icon-57x57.png', true) ?>">
        <link rel="apple-touch-icon" sizes="60x60" href="<?= Url::to('@web/img/favicon/apple-icon-60x60.png', true) ?>">
        <link rel="apple-touch-icon" sizes="72x72" href="<?= Url::to('@web/img/favicon/apple-icon-72x72.png', true) ?>">
        <link rel="apple-touch-icon" sizes="76x76" href="<?= Url::to('@web/img/favicon/apple-icon-76x76.png', true) ?>">
        <link rel="apple-touch-icon" sizes="114x114" href="<?= Url::to('@web/img/favicon/apple-icon-114x114.png', true) ?>">
        <link rel="apple-touch-icon" sizes="120x120" href="<?= Url::to('@web/img/favicon/apple-icon-120x120.png', true) ?>">
        <link rel="apple-touch-icon" sizes="144x144" href="<?= Url::to('@web/img/favicon/apple-icon-144x144.png', true) ?>">
        <link rel="apple-touch-icon" sizes="152x152" href="<?= Url::to('@web/img/favicon/apple-icon-152x152.png', true) ?>">
        <link rel="apple-touch-icon" sizes="180x180" href="<?= Url::to('@web/img/favicon/apple-icon-180x180.png', true) ?>">
        <link rel="icon" type="image/png" sizes="192x192"  href="<?= Url::to('@web/img/favicon/android-icon-192x192.png', true) ?>">
        <link rel="icon" type="image/png" sizes="32x32" href="<?= Url::to('@web/img/favicon/favicon-32x32.png', true) ?>">
        <link rel="icon" type="image/png" sizes="96x96" href="<?= Url::to('@web/img/favicon/favicon-96x96.png', true) ?>">
        <link rel="icon" type="image/png" sizes="16x16" href="<?= Url::to('@web/img/favicon/favicon-16x16.png', true) ?>">
        <link rel="manifest" href="<?= Url::to('@web/img/favicon/manifest.json', true) ?>">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="<?= Url::to('@web/img/favicon/ms-icon-144x144.png', true) ?>">
        <meta name="theme-color" content="#ffffff">

        <?php $this->registerCsrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>

        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
        <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>

    </head>

    <body>

        <?php $this->beginBody() ?>

        <?php include __DIR__.'/_nav.php'; ?>

        <div class="wrapper">
            <?= $content ?>
        </div>

        <?php // include __DIR__.'/_footer.php'; ?>

        <?= Modals::widget(['modals' => isset($this->params['modals']) ? $this->params['modals'] : [],]); ?>

        <script>
            $(function () {
<?php if (Yii::$app->session->hasFlash('success')) { ?>
                    app.notify("<?= Yii::$app->session->getFlash('success') ?>", "success", 1000);
<?php } else if (Yii::$app->session->hasFlash('error')) { ?>
                    app.notify("<?= Yii::$app->session->getFlash('error') ?>", "danger", 1000);
<?php } ?>
            });
        </script>
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
