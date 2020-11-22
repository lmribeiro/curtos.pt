<?php

use yii\widgets\ActiveForm;

?>
<?php
$form = ActiveForm::begin([
            'id' => 'form-delete',
        ]);

?>

<div id="delete_modal" class="modal fade" role="dialog" arian-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= Yii::t('app', 'Apagar') ?></h5>
                <button class="close" data-dismiss="modal">×</button>
            </div>
            <div class="modal-body">
                <?= Yii::t('app', 'Tens a certeza que queres apagar este link?') ?>
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
    $('.btn-delete').on('click', function () {
        url = "/link/delete?id=" + $(this).data('id');
        $('#form-delete').attr('action', url);
    });

    $('#form-delete').submit(function () {
        $('#delete_modal').modal('hide');
    });
</script>
