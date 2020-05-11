<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\modules\menu\models\MainMenu */

$this->title = Yii::t('app', 'Create Main Menu');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Main Menus'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="main-menu-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
