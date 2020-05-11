<?php

use common\modules\menu\models\Menu;
use common\modules\menu\modules\admin\widgets\Nestable;
use \langs\widgets\LangsWidgets;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

$this->title = Yii::t('main', 'Menu');
$this->params['breadcrumbs'][] = $this->title;

$js = <<<JS
	$('.js-create-menu').on('click', function(e) 
	{
		e.preventDefault();
    	$('#create-menu-modal').modal('show');
	});
JS;
?>
<div class="content">
    <div class="container-fluid   container-fixed-lg">
        <div class="row">
            <div class="col-lg-8">
                <div class="card card-default">
                    <div class="card-body">
                        <div class="table-responsive">
                            <div class="cf nestable-lists" id="basicTable_wrapper">
                                <p>
                                    <?= Html::a(Yii::t('backend', 'Create Menu'), ['create'], ['class' => 'btn btn-success js-create-menu']) ?>
                                </p>
                                <div>
                                    <?php Pjax::begin([
                                        'timeout' => false,
                                        'id' => 'menu-pjax'
                                    ]);
                                    $this->registerJs($js);
                                    ?>
                                    <div class="dd" id="menu-nestable">
                                        <ol class="dd-list">
                                            <?php
                                            $items = Menu::find()->andWhere(['parent_id' => NULL])->andWhere(['main_menu' => $id])->orderBy(['sort' => SORT_ASC])->all();
                                            if (count($items)) {
                                                echo Nestable::widget([
                                                    'id' => 'menu-nestable',
                                                    'items' => $items,
                                                    'url' => Url::to(['menu/update'])
                                                ]);
                                            } else {
                                                echo '<h3 class="text-center">' . Yii::t('main', 'Нет субъектов') . '</h3>';
                                            }
                                            ?>
                                        </ol>
                                    </div>
                                    <?php Pjax::end() ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->render('_modal', [
    'model' => new Menu(),
    'id' => $id
]) ?>
