<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить пользователя', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget(
        [
            'dataProvider'   => $dataProvider,
            'summary'        => 'Показано {begin}-{end} из {totalCount} элементов',
            'summaryOptions' => [
                'class' => 'summary',
                'style' => 'margin-bottom: 20px;',
            ],
            'filterModel'    => $searchModel,
            'columns'        => [
                [
                    'attribute' => 'id',
                    'contentOptions' => ['style' => 'width: 50px; text-align: center;'],
                    'headerOptions' => ['style' => 'width: 50px;'],
                ],
                'name:ntext',
                'email:email',
                [
                    'attribute' => 'created_at',
                    'format'    => ['datetime', 'php:d.m.Y H:i'],
                    'filter'    => false,
                ],
                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]
    ); ?>
</div>