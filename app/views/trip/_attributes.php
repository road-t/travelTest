<?php

use yii\helpers\Html;

/* @var $attributes app\models\ServiceAttribute[] */

foreach ($attributes as $attribute): ?>
    <div class="form-group">
        <?= Html::label($attribute->label, "attributes[$attribute->id]") ?>
        <?php if ($attribute->data_type === 'boolean'): ?>
            <?= Html::checkbox("attributes[$attribute->id]", false, ['value' => 1]) ?>
        <?php else: ?>
            <?= Html::input('text', "attributes[$attribute->id]", null, ['class' => 'form-control']) ?>
        <?php endif; ?>
    </div>
<?php endforeach; ?>