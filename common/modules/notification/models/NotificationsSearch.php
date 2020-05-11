<?php

namespace common\modules\notification\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\notification\models\Notifications;

/**
 * NotificationsSearch represents the model behind the search form of `common\models\Notifications`.
 */
class NotificationsSearch extends Notifications
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['notification_id', 'status', 'user_id', 'type', 'created_at', 'updated_at'], 'integer'],
            [['title', 'message'], 'safe'],
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
        $query = Notifications::find();

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
            'notification_id' => $this->notification_id,
            'status' => $this->status,
            'user_id' => $this->user_id,
            'type' => $this->type,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['ilike', 'title', $this->title])
            ->andFilterWhere(['ilike', 'message', $this->message]);

        return $dataProvider;
    }
}
