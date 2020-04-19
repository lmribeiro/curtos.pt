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

        <link rel="icon" type="image/png" href="<?= Url::to('@web/icon.png', true) ?>">

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
