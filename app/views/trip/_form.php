<?php

use app\models\User;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Trip */
/* @var $form yii\widgets\ActiveForm */

$users = ArrayHelper::map(User::find()->orderBy('name')->all(), 'id', 'name');
?>

<div class="trip-form">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'start_date')->widget(DatePicker::class, [
        'dateFormat' => 'yyyy-MM-dd',
        'options' => ['class' => 'form-control'],
        'clientOptions' => [
            'changeMonth' => true,
            'changeYear' => true,
            'yearRange' => '2025:2050',
        ],
    ]) ?>

    <?= $form->field($model, 'end_date')->widget(DatePicker::class, [
        'dateFormat' => 'yyyy-MM-dd',
        'options' => ['class' => 'form-control'],
        'clientOptions' => [
            'changeMonth' => true,
            'changeYear' => true,
            'yearRange' => '2020:2050',
        ],
    ]) ?>

    <?= $form->field($model, 'participantIds')->listBox(
        $users,
        [
            'multiple' => true,
            'size' => 10,
            'options' => array_map(
                fn($userId) => ['selected' => in_array($userId, $model->participantIds ?? [])],
                array_keys($users)
            ),
        ]
    )->label('Участники командировки') ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>