<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Trip;
use app\models\ServiceType;

/* @var $this yii\web\View */
/* @var $model app\models\Service */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="service-form">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'trip_id')->dropDownList(
        ArrayHelper::map(Trip::find()->all(), 'id', 'name'),
        ['prompt' => 'Выберите командировку']
    ) ?>

    <?= $form->field($model, 'service_type_id')->dropDownList(
        ArrayHelper::map(ServiceType::find()->all(), 'id', 'name'),
        ['prompt' => 'Выберите тип услуги']
    ) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'start_date')->input('datetime-local') ?>

    <?= $form->field($model, 'end_date')->input('datetime-local') ?>

    <?= $form->field($model, 'is_confirmed')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>