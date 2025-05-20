<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

class TripUser extends ActiveRecord
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            [['trip_id', 'user_id'], 'required'],
            [['trip_id', 'user_id'], 'integer'],
            [['user_id'], 'exist', 'skipOnError' => false,
             'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['trip_id'], 'exist', 'skipOnError' => false,
             'targetClass' => Trip::class, 'targetAttribute' => ['trip_id' => 'id']],
        ];
    }

    /**
     * @return string
     */
    public static function tableName() : string
    {
        return 'trip_user';
    }

    /**
     * @return ActiveQuery
     */
    public function getUser() : ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getTrip() : ActiveQuery
    {
        return $this->hasOne(Trip::class, ['id' => 'trip_id']);
    }
}