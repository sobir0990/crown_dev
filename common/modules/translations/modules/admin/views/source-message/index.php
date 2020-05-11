    <?php

    use kartik\daterange\DateRangePicker;
    use kartik\editable\Editable;
    use kartik\select2\Select2;
    use yii\data\Sort;
    use yii\helpers\Html;

    /* @var $this yii\web\View */
    /* @var $searchModel common\modules\translations\models\SourceMessageSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */

    $this->title = 'Source Messages';
    $this->params['breadcrumbs'][] = $this->title;
    $langs = \jakharbek\langs\models\Langs::find()->all();
    $sort = new Sort([
        'attributes' => [
            'message' => [
                'asc' => ['message' => SORT_ASC],
                'desc' => ['message' => SORT_DESC],
                'default' => SORT_DESC,
                'label' => 'Message',
            ],
        ],
    ]);
    $sources = \common\modules\translations\models\SourceMessage::find()->orderBy($sort->orders)->all();
    $s = 0;
    ?>
    <style>
        .popover {
            display: none;
        }
    </style>
    <div class="content">
        <div class="container-fluid container-fixed-lg bg-white">
            <div class="card card-transparent">
                <div class="card-body">
                    <div class="table-responsive">
                        <div class="dataTables_wrapper no-footer" id="basicTable_wrapper">
                            <div class="panel">
                                <div class="panel-heading">
                                    <h1><?= __('Translations') ?></h1>
                                    <p>
                                        <?= Html::a('Create', ['create'], ['class' => 'btn btn-success']) ?>
                                    </p>
                                </div>
                                <div class="panel-body">

                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <td>#</td>
                                            <td><?= $sort->link('message') ?></td>
                                            <?php foreach ($langs as $lang): ?>
                                                <td>
                                                    <?php echo $lang->name; ?>
                                                </td>
                                            <?php endforeach; ?>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($sources as $source): $messages = $source->messages; ?>
                                            <?php $s++; ?>
                                            <tr>
                                                <td>
                                                    <?= $source->id ?>
                                                </td>
                                                <td>
                                                    <?= $source->message ?>
                                                </td>
                                                <?php foreach ($langs as $lang): ?>
                                                    <?php
                                                    $value_query = \common\modules\translations\models\Message::find()->where(['id' => $source->id])->andWhere(['language' => $lang->code]);
                                                    if ($value_query->count() == 0) {
                                                        $value_lang = $source->message;
                                                    } else {
                                                        $value_lang = $value_query->one()->translation;
                                                    }
                                                    ?>
                                                    <td>
                                                        <?php
                                                        echo Editable::widget([
                                                            'name' => 'translation[' . $lang->code . '][' . $source->id . ']',
                                                            'asPopover' => true,
                                                            'value' => $value_lang,
                                                            'header' => 'Name',
                                                            'size' => 'md',
                                                            'options' => ['class' => 'form-control', 'placeholder' => 'Enter person name...'],
                                                            'additionalData' => ['hasEditable' => true]
                                                        ]);
                                                        ?>
                                                    </td>
                                                <?php endforeach; ?>
                                            </tr>
                                        <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
