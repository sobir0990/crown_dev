<?php
Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@api', dirname(dirname(__DIR__)) . '/api');
Yii::setAlias('@console', dirname(dirname(__DIR__)) . '/console');
Yii::setAlias('@static', dirname(dirname(__DIR__)) . '/static');

function __($message,$category = "main", $params = array())
{
    return Yii::t($category, $message, $params, Yii::$app->language);
}
