<?php

use common\modules\menu\models\Menu;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$model = new Menu();

$create_action = Url::to(['menu/create', 'id' => isset($parent_subject_id) ? $parent_subject_id : null]);
$js = <<<JS
	var submited = false; 
	$('#create-menu-form').on('beforeSubmit', function(event, jqXHR, settings){
        var form = $(this);
        if( form.find('.has-error').length ){
            return false;
        }
        if (submited == false ){
            submited = true;
            $('#menu_submit').attr('disabled', true);
            $.ajax({
                url: '{$create_action}',
                type: 'POST',
                data: form.serialize(),
                success: function(data){
                    if( data.status == true ){
                        form[0].reset();
				        $('#create-menu-modal').modal('hide');
				        $.pjax.reload({container: "#menu-pjax"});
                    }
                    submited = false;
                    $('#menu_submit').attr('disabled', false);
                }
            });
        }
        return false;
    }).on('submit', function(e){
        e.preventDefault();
        return false;
    });
	
JS;
$this->registerJs($js);
?>
<div class="modal stick-up" id="create-menu-modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header clearfix text-left">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					<i class="pg-close fs-14"></i>
				</button>
				<h5><span class="semi-bold"><?= Yii::t('main', 'Create Menu') ?></span></h5>
			</div>
			<div class="modal-body">
				<?php $form = ActiveForm::begin([
					'id' => 'create-menu-form'
				]); ?>
				<?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
				<?= $form->field($model, 'url')->textarea(['rows' => 3]) ?>
				<?= $form->field($model, 'main_menu')->hiddenInput(['value' => $id])->label(false) ?>
                <div class="col-lg-12 form-group">
                    <div class="form-group">
                        <h6><?= __('Full') ?></h6>
                        <?= $form->field($model, 'full_width')->checkbox(['data-init-plugin' => 'switchery', 'label' => false, 'data-size' => 'small']) ?>
                    </div>
                </div>
					<?= Html::submitButton(Yii::t('main', 'Create'), ['class' => 'btn btn-primary', 'id' => 'menu_submit']) ?>
				<?php ActiveForm::end(); ?>
			</div>
		</div>
	</div>
</div>
