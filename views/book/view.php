<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Book $model */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Books', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="book-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (!Yii::$app->user->isGuest): ?>
        <p>
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>

            <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Вы уверены что хотите удалить эту книгу?',
                    'method' => 'post',
                ],
            ]) ?>
        </p>
    <?php endif; ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'publication_year',
            'description:ntext',
            'isbn',
            [
                'label' => 'Авторы',
                'value' => implode(', ', ArrayHelper::getColumn($model->authors, 'full_name')),
            ],
        ],
    ]) ?>
    <?php if ($model->image_url): ?>
        <h3>Обложка</h3>

        <?= Html::img($model->image_url, [
            'alt' => $model->title,
            'style' => 'max-width: 300px; height: auto;',
        ]) ?>
    <?php endif; ?>

</div>
