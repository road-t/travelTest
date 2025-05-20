<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Trip */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Командировки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="trip-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить эту командировку?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Добавить услугу', ['add-service', 'trip_id' => $model->id], ['class' => 'btn btn-success']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
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
                'label' => 'Продолжительность',
                'value' => function($model) {
                    $start = new DateTime($model->start_date);
                    $end = new DateTime($model->end_date);
                    return $start->diff($end)->days + 1 . ' дней';
                },
            ],
            [
                'attribute' => 'created_at',
                'format' => ['datetime', 'php:d.m.Y H:i'],
            ],
        ],
    ]) ?>

    <h2>Участники командировки</h2>
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Пользователь</th>
                <th>Дата начала</th>
                <th>Дата окончания</th>
            </tr>
        </thead>
        <tbody>
            <!--TODO: list users-->
        </tbody>
    </table>

    <h2>Услуги командировки</h2>
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Тип услуги</th>
                <th>Название</th>
                <th>Дата начала</th>
                <th>Дата окончания</th>
                <th>Статус</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <!--TODO: list services -->
        </tbody>
    </table>
</div>