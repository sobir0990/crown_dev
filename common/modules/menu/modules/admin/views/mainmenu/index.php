<?php

use jakharbek\langs\widgets\LangsWidgets;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel common\modules\menu\models\MainMenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Main Menus');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content">
    <div class="container-fluid container-fixed-lg bg-white">
        <div class="card card-transparent">
            <div class="card-body">
                <div class="table-responsive">
                    <div class="dataTables_wrapper no-footer" id="basicTable_wrapper">
                        <p>
                            <?= Html::a(Yii::t('backend', 'Create Menu'), ['create'], ['class' => 'btn btn-success']) ?>
                        </p>
                        <?php echo LangsWidgets::widget(); ?>
                        <div>
                            <?= GridView::widget([
                                'dataProvider' => $dataProvider,
                                'tableOptions' => [
                                    'class' => 'table table-hover dataTable no-footer',
                                    'id' => 'basicTable'
                                ],
                                'options' => [
                                    'tag' => false
                                ],
                                'columns' => [
                                    ['class' => 'yii\grid\SerialColumn'],

                                    'id',
                                    [
                                        'label' => __('Title'),
                                        'attribute' => 'title',
                                        'value' => function($model){
                                            return Html::a($model->title, "/menu/menu/{$model->id}");
                                        },
                                        'format' => 'raw'
                                    ],
                                    'slug',

                                    [
                                        'class' => 'yii\grid\ActionColumn',
                                        'template' => '{update} {delete}',
                                        'buttons' => [
                                            'update' => function ($model) {
                                                return "<div class='btn btn-success'>" . Html::a(
                                                        '<i class="fa fa-pencil" style="color: #ffffff;"></i>', $model
                                                    ) . '</div>';
                                            },
                                            'delete' => function ($url, $model) {
                                                return "<div class='btn btn-success'>" . Html::a(
                                                        '<i class="fa fa-trash-o" style="color: #ffffff;"></i>',
                                                        $url, ['data-method' => 'post']) . '</div>';
                                            },
                                        ],
                                    ],
                                ],
                            ]); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
