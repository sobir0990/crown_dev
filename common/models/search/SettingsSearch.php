<?php

namespace common\models\search;

use common\models\Settings;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ReferenceSearch represents the model behind the search form of `common\models\Reference`.
 */
class SettingsSearch extends Settings
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email', 'phone', 'address', 'longitude', 'latitude'], 'string', 'max' => 254],
            [['course'], 'double']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
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
        $query = Settings::find();

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
            'phone' => $this->phone,
            'email' => $this->email,
        ]);

        $query->andFilterWhere(['ilike', 'phone', $this->phone])
            ->andFilterWhere(['ilike', 'longitude', $this->longitude])
            ->andFilterWhere(['ilike', 'latitude', $this->latitude])
            ->andFilterWhere(['ilike', 'email', $this->email]);
        return $dataProvider;
    }
}
