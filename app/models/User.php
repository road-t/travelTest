<?php

namespace app\models;

use yii\db\ActiveRecord;

class User extends ActiveRecord
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            [['name', 'email'], 'required'],
            ['email', 'email'],
            ['email', 'unique'],
            [['name', 'email'], 'string', 'max' => 255],
            ['created_at', 'default', 'value' => new \yii\db\Expression('NOW()')],
        ];
    }

    /**
     * @return string
     */
    public static function tableName() : string
    {
        return 'user';
    }

    /**
     * @return \yii\db\ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public function getTrips() : \yii\db\ActiveQuery
    {
        return $this->hasMany(Trip::class, ['id' => 'trip_id'])
            ->viaTable('trip_user', ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public function getServices() : \yii\db\ActiveQuery
    {
        return $this->hasMany(Service::class, ['id' => 'service_id'])
            ->viaTable('service_user', ['user_id' => 'id']);
    }

    /**
     * @return string[]
     */
    public function attributeLabels() : array
    {
        return [
            'id' => 'ID',
            'name' => 'ФИО',
            'email' => 'e-mail',
            'created_at' => 'Дата регистрации',
            // Добавьте другие поля
        ];
    }
}