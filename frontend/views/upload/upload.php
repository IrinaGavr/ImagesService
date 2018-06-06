<?php

use yii\widgets\ActiveForm;
?>

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

<?= $form->field($model, 'file')->fileInput() ?>
<?= $form->field($model, 'model_name')->hint('Заполните имя модели') ?>
<?= $form->field($model, 'model_id')->hint('Заполните id') ?>
<?= $form->field($model, 'desc')->hint('Заполните инфу о файле') ?>

<button>Submit</button>

<?php echo Yii::$app->session->getFlash('success'); ?>


    <?php
    echo "<PRE>";
    var_dump($model->GetAttributes());
    var_dump($model->getErrors());
    var_dump($model->validate());
    echo "</PRE>";
    ?>

<?php ActiveForm::end() ?>