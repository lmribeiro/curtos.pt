<?php

use yii\widgets\ActiveForm;
use yii\helpers\Url;

?>
<?php
$form = ActiveForm::begin([
            'id' => 'form-api-key',
            'action' => Url::to('renew-api-key')
        ]);

?>

<div id="apiKey_modal" class="modal fade" role="dialog" arian-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= Yii::t('app', 'API') ?></h5>
                <button class="close" data-dismiss="modal">×</button>
            </div>
            <div class="modal-body">
                <?= Yii::t('app', 'Tem a certeza que pretende regenerar a chave da API?') ?>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" type="submit">
                    <?= Yii::t('app', 'OK'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>
<script>
    $('#form-api-key').submit(function () {
        $('#apiKey_modal').modal('hide');
    });
</script>