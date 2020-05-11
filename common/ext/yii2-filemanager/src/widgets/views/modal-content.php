<?php
use kartik\file\FileInput;
use yii\helpers\Url; ?>
<?php
echo FileInput::widget([
    'name' => 'files[]',
    'options' => ['multiple' => true],
    'pluginOptions' => ['previewFileType' => 'any', 'uploadUrl' => Url::to(['/files/files/upload']),]
]);
?>
<?php $form = \yii\bootstrap4\ActiveForm::begin(['id' => 'file-manager-search-form', 'options' => ['data-pjax' => 1], 'method' => 'get']); ?>
<?= $form->field($search_model, 's')->textInput(['autofocus' => true]) ?>
<?php \yii\bootstrap4\ActiveForm::end(); ?>
<div class="container">
    <?= \yii\widgets\ListView::widget([
        'dataProvider' => $dataProvider,
        'layout' => '{items}{pager}',
        'options' => [
                'class' => 'row'
        ],
        'itemView' => 'modal-view',
        'itemOptions' => [
                'class' => 'col-6'
        ],
        'pager' => [
            'options' => [
                'tag' => 'div',
                'class' => 'pagination file-manager-pager-wrapper'
            ],
            'linkOptions' => [
                    'class' => 'page-link',
            ],
            'disabledPageCssClass' => 'page-link',
        ],
        'viewParams' => ['dataProvider' => $dataProvider, 'btn_check_js_func' => $btn_check_js_func, 'btn_check' => $btn_check],
    ]); ?>
</div>

