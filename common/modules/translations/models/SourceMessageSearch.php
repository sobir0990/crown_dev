<?php

namespace common\modules\translations\models;


use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\posts\models\Posts;
use yii\helpers\ArrayHelper;

use common\modules\translations\models\SourceMessage;

/**
 * SourceMessageSearch represents the model behind the search form of `common\modules\translations\models\SourceMessage`.
 */
class SourceMessageSearch extends SourceMessage
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
                [["id","category","message",],'safe']
              ];
    }

    public function init()
    {
        parent::init();
    }
    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = SourceMessage::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['ilike', 'category', $this->category])
            ->andFilterWhere(['ilike', 'message', $this->message]);

        return $dataProvider;
    }
}
