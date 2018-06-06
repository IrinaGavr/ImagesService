<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Upload */




$this->title = 'Update Upload: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Uploads', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>
<?= $form->field($model, 'file')->fileInput() ?>
<div class="upload-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

    <?php echo Yii::$app->session->getFlash('success'); ?>

    <?php
    echo "<PRE>";
    var_dump($model->GetAttributes());
    var_dump($model->getErrors());
    var_dump($model->validate());
    echo "</PRE>";
    ?>

</div>
