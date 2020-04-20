<?php

use yii\widgets\ActiveForm;

?>
<?php
$form = ActiveForm::begin([
            'id' => 'form-renew',
        ]);

?>

<div id="renew_modal" class="modal fade" role="dialog" arian-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= Yii::t('app', 'Renovar data Expiração?') ?></h5>
                <button class="close" data-dismiss="modal">×</button>
            </div>
            <div class="modal-body">
                <?= Yii::t('app', 'Tem a certeza que pretende renovar a data de expiração deste link?') ?>
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
    $('.btn-renew').on('click', function () {
        url = "link/renew?id=" + $(this).data('id');
        $('#form-renew').attr('action', url);
    });

    $('#form-renew').submit(function () {
        $('#renew_modal').modal('hide');
    });
</script>