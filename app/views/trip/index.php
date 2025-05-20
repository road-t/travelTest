<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TripSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Командировки';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="trip-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать командировку', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'id',
                'contentOptions' => ['style' => 'width: 50px; text-align: center;'],
                'headerOptions' => ['style' => 'width: 50px;'],
            ],
            'name',
            [
                'attribute' => 'start_date',
                'format' => ['date', 'php:d.m.Y'],
            ],
            [
                'attribute' => 'end_date',
                'format' => ['date', 'php:d.m.Y'],
            ],
            [
                'attribute' => 'duration',
                'label' => 'Длительность',
                'value' => function($model) {
                    $start = new DateTime($model->start_date);
                    $end = new DateTime($model->end_date);
                    $diff = $start->diff($end);
                    return $diff->days + 1 . ' дн.';
                },
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>