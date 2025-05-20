<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Trip */

$this->title = 'Создание командировки';
$this->params['breadcrumbs'][] = ['label' => 'Командировки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="trip-create">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', ['model' => $model]) ?>
</div>