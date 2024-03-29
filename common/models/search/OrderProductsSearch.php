<?php

namespace common\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\OrderProducts;

/**
 * OrderProductsSearch represents the model behind the search form of `common\models\OrderProducts`.
 */
class OrderProductsSearch extends OrderProducts
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'product_id', 'product_user_id', 'product_models_id', 'product_models_category_id'], 'integer'],
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
        $query = OrderProducts::find();

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
            'order_id' => $this->order_id,
            'product_id' => $this->product_id,
            'product_user_id' => $this->product_user_id,
            'product_models_id' => $this->product_models_id,
            'product_models_category_id' => $this->product_models_category_id,
        ]);

        return $dataProvider;
    }
}
