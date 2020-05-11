<?php
/**
 * Created by PhpStorm.
 * User: OKS
 * Date: 13.05.2019
 * Time: 17:37
 */

use jakharbek\langs\widgets\LangsWidgets;
use yii\widgets\ActiveForm;

?>
<div class="content">

    <div class="container-fluid   container-fixed-lg">
        <div class="row">
            <h3 class="col-lg-12"><?= $this->title ?></h3>
        </div>
        <p>
            <?= \yii\helpers\Html::a(Yii::t('backend', 'Menus'), '/menu/menu', ['class' => 'btn btn-success']) ?>
        </p>
        <?php echo LangsWidgets::widget(['model_db' => $model, 'create_url' => '/menu/menu/create-menu']); ?>

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
                        <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
                        <?= $form->field($model, 'url')->textarea(['rows' => 3]) ?>
                        <div class="col-lg-12 form-group">
                            <div class="form-group">
                                <h6><?= __('Full') ?></h6>
                                <?= $form->field($model, 'full_width')->checkbox(['data-init-plugin' => 'switchery', 'label' => false, 'data-size' => 'small']) ?>
                            </div>
                        </div>
                        <?= \yii\helpers\Html::submitButton(Yii::t('main', 'Update'), ['class' => 'btn btn-primary', 'id' => 'menu_submit']) ?>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
