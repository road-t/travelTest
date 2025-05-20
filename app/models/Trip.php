<?php

namespace app\models;

use Yii;
use DateTime;
use yii\db\ActiveRecord;

class Trip extends ActiveRecord
{
    private $_participantIds;

    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
            ['name', 'filter', 'filter' => 'trim'],

            [['created_at'], 'safe'],

            [['start_date', 'end_date'], 'date', 'format' => 'php:Y-m-d'],
            ['end_date', 'compare', 'compareAttribute' => 'start_date', 'operator' => '>='],
        ];
    }

    /**
     * @return string
     */
    public static function tableName() : string
    {
        return 'trip';
    }

    /**
     * @return int
     * @throws \DateMalformedStringException
     */
    public function getDuration() : int
    {
        $start = new DateTime($this->start_date);
        $end = new DateTime($this->end_date);
        return $start->diff($end)->days + 1;
    }

    /**
     * @return \yii\db\ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public function getUsers() : \yii\db\ActiveQuery
    {
        return $this->hasMany(User::class, ['id' => 'user_id'])
                    ->viaTable('trip_user', ['trip_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public function getServices() : \yii\db\ActiveQuery
    {
        return $this->hasMany(Service::class, ['id' => 'user_id'])
                    ->viaTable('service_user', ['service_id' => 'id']);
    }

    /**
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function getParticipantIds() : array
    {
        if ($this->_participantIds === null) {
            $this->_participantIds = $this->getUsers()->select('id')->column();
        }
        return $this->_participantIds;
    }

    /**
     * @param $ids
     *
     * @return void
     */
    public function setParticipantIds($ids) : void
    {
        $this->_participantIds = $ids;
    }

    /**
     * @return void
     */
    public function updateUsersDates() : void
    {
        $dates = Service::find()
                        ->select([
                            'user_id',
                            'MIN(start_date) as min_start',
                            'MAX(end_date) as max_end'
                        ])
                        ->joinWith('users')
                        ->where([
                            'service.trip_id' => $this->id,
                            'service.is_confirmed' => true
                        ])
                        ->groupBy('user_id')
                        ->asArray()
                        ->all();

        foreach ($dates as $dateInfo) {
            TripUser::updateAll([
                'start_date' => $dateInfo['min_start'],
                'end_date' => $dateInfo['max_end']
            ], [
                'trip_id' => $this->id,
                'user_id' => $dateInfo['user_id']
            ]);
        }
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

        if (isset($this->participantIds)) {
            $this->updateParticipants();
        }

        $this->updateUsersDates();
    }

    /**
     * @return void
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     */
    protected function updateParticipants() : void
    {
        $currentIds = $this->getParticipantIds();
        $newIds = $this->participantIds ?? [];

        $toRemove = array_diff($currentIds, $newIds);
        if ($toRemove) {
            TripUser::deleteAll(['trip_id' => $this->id, 'user_id' => $toRemove]);
        }

        $toAdd = array_diff($newIds, $currentIds);
        foreach ($toAdd as $userId) {
            $relation = new TripUser([
                'trip_id' => $this->id,
                'user_id' => $userId,
            ]);
            $relation->save();
        }
    }

    /**
     * @return string[]
     */
    public function attributeLabels() : array
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'created_at' => 'Дата создания',
            'start_date' => 'Дата начала',
            'end_date' => 'Дата окончания',
        ];
    }
}