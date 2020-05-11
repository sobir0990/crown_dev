<?php

namespace common\modules\playmobile\models\query;

/**
 * This is the ActiveQuery class for [[\common\modules\playmobile\models\Smslog]].
 *
 * @see \common\modules\playmobile\models\Smslog
 */
class SmslogQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \common\modules\playmobile\models\Smslog[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\modules\playmobile\models\Smslog|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
