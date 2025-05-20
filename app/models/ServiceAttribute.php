<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

class ServiceAttribute extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName() : string
    {
        return 'service_attribute';
    }

    /**
     * @return ActiveQuery
     */
    public function getServiceType() : ActiveQuery
    {
        return $this->hasOne(ServiceType::class, ['id' => 'service_type_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getValues() : ActiveQuery
    {
        return $this->hasMany(ServiceAttributeValue::class, ['attribute_id' => 'id']);
    }
}