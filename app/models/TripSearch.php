<?php

namespace app\models;

use yii\data\ActiveDataProvider;

class TripSearch extends Trip
{
    /**
     * @return array[]
     */
    public function rules() : array
    {
        return [
            [['id'], 'integer'],
            [['name', 'start_date', 'end_date'], 'safe'],
        ];
    }

    /**
     * @param $query
     * @param $attribute
     *
     * @return void
     */
    protected function addDateFilter($query, $attribute) : void
    {
        if ($this->$attribute) {
            $query->andFilterWhere([$attribute => $this->$attribute]);
        }
    }

    /**
     * @param $params
     *
     * @return ActiveDataProvider
     */
    public function search($params) : ActiveDataProvider
    {
        $query = Trip::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['start_date' => SORT_DESC],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'name', $this->name]);
        $this->addDateFilter($query, 'start_date');
        $this->addDateFilter($query, 'end_date');

        return $dataProvider;
    }
}