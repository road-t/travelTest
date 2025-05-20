<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\ServiceType;

/* @var $this yii\web\View */
/* @var $model app\models\Service */

$this->title = 'Редактировать услугу: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Командировки', 'url' => ['trip/index']];
$this->params['breadcrumbs'][] = ['label' => $model->trip->name, 'url' => ['trip/view', 'id' => $model->trip_id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="service-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="service-form">
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'service_type_id')->dropDownList(
            ArrayHelper::map(ServiceType::find()->all(), 'id', 'name'),
            ['prompt' => '-- Выберите тип услуги --']
        ) ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'start_date')->input('datetime-local') ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'end_date')->input('datetime-local') ?>
            </div>
        </div>

        <?= $form->field($model, 'is_confirmed')->checkbox([
            'label' => 'Подтверждено',
        ]) ?>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Отмена', ['trip/view', 'id' => $model->trip_id], ['class' => 'btn btn-default']) ?>
            <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger pull-right',
                'data' => [
                    'confirm' => 'Вы уверены, что хотите удалить эту услугу?',
                    'method' => 'post',
                ],
            ]) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>