<?php

use yii\widgets\ActiveForm;
use yii\helpers\Url;

?>
<?php
$form = ActiveForm::begin([
            'id' => 'form-account-delete',
            'action' => Url::to('account-delete')
        ]);

?>

<div id="delete_modal" class="modal fade" role="dialog" arian-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= Yii::t('app', 'Apagar Conta') ?></h5>
                <button class="close" data-dismiss="modal">×</button>
            </div>
            <div class="modal-body">
                <?= Yii::t('app', 'Tem a certeza que pretende apagar a sua conta?') ?>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" type="submit">
                    <?= Yii::t('app', 'Apagar'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>
<script>
    $('#form-account-delete').submit(function () {
        $('#delete_modal').modal('hide');
    });
</script>