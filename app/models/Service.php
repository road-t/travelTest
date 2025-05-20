<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

class Service extends ActiveRecord
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            [['trip_id', 'service_type_id', 'name', 'start_date', 'end_date'], 'required'],
            [['trip_id', 'service_type_id'], 'integer'],
            [['start_date', 'end_date'], 'datetime', 'format' => 'php:m.d.Y H:i'],
            ['end_date', 'compare', 'compareAttribute' => 'start_date', 'operator' => '>='],
            [['is_confirmed'], 'boolean'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @return string[]
     */
    public function attributeLabels() : array
    {
        return [
            'id' => 'ID',
            'trip_id' => 'Командировка',
            'service_type_id' => 'Тип услуги',
            'name' => 'Название',
            'start_date' => 'Дата начала',
            'end_date' => 'Дата окончания',
            'is_confirmed' => 'Подтверждено',
            'created_at' => 'Дата создания',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getTrip() : ActiveQuery
    {
        return $this->hasOne(Trip::class, ['id' => 'trip_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getServiceType() : ActiveQuery
    {
        return $this->hasOne(ServiceType::class, ['id' => 'service_type_id']);
    }

    /**
     * @return string
     */
    public static function tableName() : string
    {
        return 'service';
    }

    /**
     * @return ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public function getUsers() : ActiveQuery
    {
        return $this->hasMany(User::class, ['id' => 'user_id'])
            ->viaTable('service_user', ['service_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getAttributeValues() : ActiveQuery
    {
        return $this->hasMany(ServiceAttributeValue::class, ['service_id' => 'id']);
    }

    /**
     * @param $insert
     * @param $changedAttributes
     *
     * @return void
     */
    public function afterSave($insert, $changedAttributes) : void
    {
        parent::afterSave($insert, $changedAttributes);
        
        // update dates if needed
        if (isset($changedAttributes['is_confirmed']) || 
            isset($changedAttributes['start_date']) || 
            isset($changedAttributes['end_date'])) {
            $this->trip->updateUsersDates();
        }
    }

    /**
     * @return bool
     */
    public function beforeValidate() : bool
    {
        if (parent::beforeValidate()) {

            $this->start_date = date('Y-m-d H:i:s', strtotime($this->start_date));
            $this->end_date = date('Y-m-d H:i:s', strtotime($this->end_date));
            return true;
        }
        return false;
    }

    /**
     * @return void
     */
    public function afterFind() : void
    {
        parent::afterFind();
        $this->start_date = date('Y-m-d\TH:i', strtotime($this->start_date));
        $this->end_date = date('Y-m-d\TH:i', strtotime($this->end_date));
    }
}