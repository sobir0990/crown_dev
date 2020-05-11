<?php

use jakharbek\langs\widgets\LangsWidgets;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\modules\menu\models\MainMenu */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="content">

    <div class="container-fluid   container-fixed-lg">
        <div class="row">
            <h3 class="col-lg-12"><?= $this->title ?></h3>
        </div>
        <?php echo LangsWidgets::widget(['model_db' => $model, 'create_url' => '/menu/mainmenu/create']); ?>
        <div class="row">
            <div class="col-lg-8">
                <div class="card card-default">
                    <div class="card-body">
                        <?php $form = ActiveForm::begin([
                            'fieldConfig' => [
                                'options' => [
                                    'tag' => false,
                                ],
                            ],
                        ]); ?>
                        <div class="form-group form-group-default required ">
                            <label><?= __('Заголовок: ') ?></label>
                            <?= $form->field($model, 'title')->textInput(['maxlength' => true])->label(false) ?>
                        </div>

                            <?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>

                        <div class="col-lg-12 form-group">
                            <div class="form-group">
                                <h6><?= __('Status') ?></h6>
                                <?= $form->field($model, 'status')->checkbox(['data-init-plugin' => 'switchery', 'label' => false, 'data-size' => 'small']) ?>
                            </div>
                        </div>

                        <?= \yii\helpers\Html::submitButton(Yii::t('main', 'Create'), ['class' => 'btn btn-primary', 'id' => 'menu_submit']) ?>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
