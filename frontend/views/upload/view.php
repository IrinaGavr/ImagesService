<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Upload */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Uploads', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>


<h1><?= Html::encode($this->title) ?></h1>

<p>
    <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    <?=
    Html::a('Delete', ['delete', 'id' => $model->id], [
        'class' => 'btn btn-danger',
        'data' => [
            'confirm' => 'Are you sure you want to delete this item?',
            'method' => 'post',
        ],
    ])
    ?>
</p>

<?=
DetailView::widget([
    'model' => $model,
    'attributes' => [
        'id',
            [
            'label' => 'file',
            'format' => 'html',
            'value' => function($model) {
//                if (exif_imagetype(common\models\Upload::getFullPath($model->path))) {
//                    return '<img class="img-responsive" src="' . $model->Image . '">';
//                }
//                return "<a href=\"{$model->Image}\" target=\"_blank\">{$model->Image}</a>";

                try {
                    if (exif_imagetype(common\models\Upload::getFullPath($model->path))) {
                        return '<img class="img-responsive" src="' . $model->Image . '">';
                    }
                } catch (yii\base\ErrorException $e) {
                    
                }
                return "<a href=\"{$model->Image}\" target=\"_blank\">{$model->Image}</a>";
            }
        ],
        'path',
        'model_name',
        'model_id',
        'desc:ntext',
    ],
])
?>








