<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'Управление командировками';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Добро пожаловать!</h1>

        <p class="lead">Система управления командировками сотрудников</p>

        <p>
            <?= Html::a('Командировки', ['/trip/index'], ['class' => 'btn btn-lg btn-primary']) ?>
            <?= Html::a('Пользователи', ['/user/index'], ['class' => 'btn btn-lg btn-success']) ?>
        </p>
    </div>

    <div class="body-content">
        <div class="row">
            <div class="col-lg-4">
                <h2>Командировки</h2>
                <p>Управление всеми командировками сотрудников. Просмотр, создание, редактирование и удаление командировок.</p>
            </div>
            <div class="col-lg-4">
                <h2>Пользователи</h2>
                <p>Управление пользователями системы. Добавление новых сотрудников и редактирование существующих.</p>
            </div>
            <div class="col-lg-4">
                <h2>Услуги</h2>
                <p>Управление услугами в командировках: авиабилеты, ж/д билеты, гостиницы и другие услуги.</p>
            </div>
        </div>
    </div>
</div>