<?php

use yii\db\Migration;

class m250519_175559_init_db extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        // Users
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'email' => $this->string()->unique(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ], $tableOptions);

        // Business trips
        $this->createTable('{{%trip}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'start_date' => $this->date()->notNull(),
            'end_date' => $this->date()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ], $tableOptions);

        // Добавляем индекс для оптимизации выборок по датам
        $this->createIndex('idx-trip-dates', 'trip', ['start_date', 'end_date']);

        // Users-Business trips
        $this->createTable('{{%trip_user}}', [
            'id' => $this->primaryKey(),
            'trip_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'start_date' => $this->date(),
            'end_date' => $this->date(),
        ], $tableOptions);

        // Services types
        $this->createTable('{{%service_type}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'description' => $this->text(),
        ], $tableOptions);

        // Services main
        $this->createTable('{{%service}}', [
            'id' => $this->primaryKey(),
            'trip_id' => $this->integer()->notNull(),
            'service_type_id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'start_date' => $this->dateTime()->notNull(),
            'end_date' => $this->dateTime()->notNull(),
            'is_confirmed' => $this->boolean()->defaultValue(false),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ], $tableOptions);

        // Services-Users
        $this->createTable('{{%service_user}}', [
            'id' => $this->primaryKey(),
            'service_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
        ], $tableOptions);

        // Service attributes
        $this->createTable('{{%service_attribute}}', [
            'id' => $this->primaryKey(),
            'service_type_id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'label' => $this->string()->notNull(),
            'data_type' => $this->string()->notNull(),
        ], $tableOptions);

        // Service attributes values
        $this->createTable('{{%service_attribute_value}}', [
            'id' => $this->primaryKey(),
            'service_id' => $this->integer()->notNull(),
            'attribute_id' => $this->integer()->notNull(),
            'value' => $this->text(),
        ], $tableOptions);

        // trip_user foreign keys
        $this->addForeignKey(
            'fk-trip_user-trip_id',
            '{{%trip_user}}',
            'trip_id',
            '{{%trip}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-trip_user-user_id',
            '{{%trip_user}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // service foreign keys
        $this->addForeignKey(
            'fk-service-trip_id',
            '{{%service}}',
            'trip_id',
            '{{%trip}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-service-service_type_id',
            '{{%service}}',
            'service_type_id',
            '{{%service_type}}',
            'id',
            'CASCADE'
        );

        // service_user foreign keys
        $this->addForeignKey(
            'fk-service_user-service_id',
            '{{%service_user}}',
            'service_id',
            '{{%service}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-service_user-user_id',
            '{{%service_user}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // service_attribute foreign keys
        $this->addForeignKey(
            'fk-service_attribute-service_type_id',
            '{{%service_attribute}}',
            'service_type_id',
            '{{%service_type}}',
            'id',
            'CASCADE'
        );

        // service_attribute_value foreign keys
        $this->addForeignKey(
            'fk-service_attribute_value-service_id',
            '{{%service_attribute_value}}',
            'service_id',
            '{{%service}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-service_attribute_value-attribute_id',
            '{{%service_attribute_value}}',
            'attribute_id',
            '{{%service_attribute}}',
            'id',
            'CASCADE'
        );

        // initial data
        $this->batchInsert('{{%service_type}}',
            ['name', 'description'],
            [
                ['Авиаперелет', 'Услуги авиаперелета'],
                ['Гостиница', 'Услуги проживания в гостинице'],
                ['ЖД билеты', 'Услуги железнодорожных перевозок'],
                ['Трансфер', 'Транспортные услуги'],
            ]
        );

        // flight attributes
        $this->batchInsert('{{%service_attribute}}',
            ['service_type_id', 'name', 'label', 'data_type'],
            [
                [1, 'flight_number', 'Номер рейса', 'string'],
                [1, 'departure_point', 'Точка отправления', 'string'],
                [1, 'arrival_point', 'Точка прибытия', 'string'],
                [1, 'seat_number', 'Номер места', 'string'],
                [1, 'class', 'Класс', 'string'],
                [1, 'has_luggage', 'Багаж', 'boolean'],
            ]
        );

        // hotel attributes
        $this->batchInsert('{{%service_attribute}}',
            ['service_type_id', 'name', 'label', 'data_type'],
            [
                [2, 'hotel_name', 'Название отеля', 'string'],
                [2, 'location', 'Локация', 'string'],
                [2, 'room_number', 'Номер комнаты', 'string'],
                [2, 'has_meal', 'Питание', 'boolean'],
            ]
        );

        // railroad attributes
        $this->batchInsert('{{%service_attribute}}',
            ['service_type_id', 'name', 'label', 'data_type'],
            [
                [3, 'train_number', 'Номер поезда', 'string'],
                [3, 'departure_station', 'Станция отправления', 'string'],
                [3, 'arrival_station', 'Станция прибытия', 'string'],
                [3, 'car_number', 'Номер вагона', 'string'],
                [3, 'seat_number', 'Номер места', 'string'],
            ]
        );

        // transfer attributes
        $this->batchInsert('{{%service_attribute}}',
            ['service_type_id', 'name', 'label', 'data_type'],
            [
                [4, 'vehicle_type', 'Тип транспорта', 'string'],
                [4, 'from_location', 'Откуда', 'string'],
                [4, 'to_location', 'Куда', 'string'],
                [4, 'driver_contact', 'Контакты водителя', 'string'],
            ]
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%service_attribute_value}}');
        $this->dropTable('{{%service_attribute}}');
        $this->dropTable('{{%service_user}}');
        $this->dropTable('{{%service}}');
        $this->dropTable('{{%service_type}}');
        $this->dropTable('{{%trip_user}}');
        $this->dropTable('{{%trip}}');
        $this->dropTable('{{%user}}');
    }
}