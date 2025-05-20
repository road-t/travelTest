<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

class UserSearch extends User
{
    /**
     * @return array[]
     */
    public function rules() : array
    {
        return [
            [['id'], 'integer'],
            [['name', 'email', 'created_at'], 'safe'],
        ];
    }

    /**
     * @param $params
     *
     * @return ActiveDataProvider
     */
    public function search($params) : ActiveDataProvider
    {
        $query = User::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_ASC,
                ]
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['id' => $this->id])
              ->andFilterWhere(['like', 'name', $this->name])
              ->andFilterWhere(['like', 'email', $this->email]);

        return $dataProvider;
    }
}