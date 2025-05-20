<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'attribute' => 'trip_id',
            'value' => 'trip.name',
        ],
        [
            'attribute' => 'service_type_id',
            'value' => 'serviceType.name',
        ],
        'name',
        [
            'attribute' => 'start_date',
            'format' => ['datetime', 'php:d.m.Y H:i'],
        ],
        [
            'attribute' => 'is_confirmed',
            'value' => function($model) {
                return $model->is_confirmed ? 'Да' : 'Нет';
            },
        ],
        ['class' => 'yii\grid\ActionColumn'],
    ],
]) ?>

<p>
    <?= Html::a('Добавить услугу', ['service/create', 'trip_id' => $model->id], ['class' => 'btn btn-success']) ?>
</p>

<?= GridView::widget([
    'dataProvider' => new \yii\data\ActiveDataProvider([
        'query' => $model->getServices(),
    ]),
    'columns' => [
        [
            'attribute' => 'service_type_id',
            'value' => 'serviceType.name',
        ],
        'name',
        [
            'attribute' => 'start_date',
            'format' => ['datetime', 'php:d.m.Y H:i'],
        ],
        [
            'attribute' => 'is_confirmed',
            'value' => function($model) {
                return Html::checkbox('confirmed', $model->is_confirmed, [
                    'disabled' => true,
                    'data-toggle' => 'toggle',
                ]);
            },
            'format' => 'raw',
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'controller' => 'service',
            'template' => '{view} {update}',
        ],
    ],
]) ?>