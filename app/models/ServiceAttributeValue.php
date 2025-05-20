<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

class ServiceAttributeValue extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName() : string
    {
        return 'service_attribute_value';
    }

    /**
     * @return ActiveQuery
     */
    public function getService() : ActiveQuery
    {
        return $this->hasOne(Service::class, ['id' => 'service_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getServiceAttribute() : ActiveQuery
    {
        return $this->hasOne(ServiceAttribute::class, ['id' => 'attribute_id']);
    }
}