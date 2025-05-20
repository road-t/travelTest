<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

class ServiceType extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName() : string
    {
        return 'service_type';
    }

    /**
     * @return ActiveQuery
     */
    public function getServices() : ActiveQuery
    {
        return $this->hasMany(Service::class, ['service_type_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getAttributesList() : ActiveQuery
    {
        return $this->hasMany(ServiceAttribute::class, ['service_type_id' => 'id']);
    }
}