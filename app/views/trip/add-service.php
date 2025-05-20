<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Service */
/* @var $trip app\models\Trip */
/* @var $serviceTypes app\models\ServiceType[] */
/* @var $users app\models\User[] */

$this->title = 'Добавить услугу';
$this->params['breadcrumbs'][] = ['label' => 'Командировки', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $trip->name, 'url' => ['view', 'id' => $trip->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="service-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'service_type_id')->dropDownList(
        ArrayHelper::map($serviceTypes, 'id', 'name'),
        ['prompt' => 'Выберите тип услуги', 'id' => 'service-type']
    ) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'start_date')->input('datetime-local') ?>

    <?= $form->field($model, 'end_date')->input('datetime-local') ?>

    <div class="form-group">
        <label class="control-label">Участники</label>
        <?= Html::checkboxList('users', null, ArrayHelper::map($users, 'id', 'name'), [
            'class' => 'form-control',
            'itemOptions' => ['labelOptions' => ['class' => 'checkbox-inline']]
        ]) ?>
    </div>

    <div id="attributes-container"></div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>