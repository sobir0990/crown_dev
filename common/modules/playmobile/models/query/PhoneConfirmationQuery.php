<?php

namespace common\modules\playmobile\models\query;

/**
 * This is the ActiveQuery class for [[\common\modules\playmobile\models\PhoneConfirmation]].
 *
 * @see \common\modules\playmobile\models\PhoneConfirmation
 */
class PhoneConfirmationQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \common\modules\playmobile\models\PhoneConfirmation[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\modules\playmobile\models\PhoneConfirmation|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
