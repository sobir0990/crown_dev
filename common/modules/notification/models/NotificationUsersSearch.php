<?php


namespace common\modules\notification\models;

use common\modules\notification\models\NotificationUsers;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class NotificationUsersSearch extends NotificationUsers
{
    public function rules()
    {
        return [
            [['message'], 'string'],
            [['status', 'user_id', 'type', 'created_at', 'updated_at'], 'default', 'value' => null],
            [['status', 'user_id', 'type', 'created_at', 'updated_at'], 'integer'],
            [['title'], 'string', 'max' => 254],
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
        $query = NotificationUsers::find();

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
            'user_id' => $this->user_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['notification_id', $this->notification_id])
            ->andFilterWhere(['user_id', $this->user_id]);

        return $dataProvider;
    }

}
