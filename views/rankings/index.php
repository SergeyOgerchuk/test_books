<?php

use yii\helpers\Html;

/** @var int $year */
/** @var array $authors */

$this->title = 'TOP-10 авторов';
?>

<h1><?= Html::encode($this->title) ?></h1>

<form method="get">
    <input type="hidden" name="r" value="rankings/index">

    <div class="mb-3">
        <label for="year" class="form-label">Год публикации</label>

        <input
            type="number"
            id="year"
            name="year"
            value="<?= Html::encode($year) ?>"
            class="form-control"
            style="max-width: 200px"
        >
    </div>

    <button type="submit" class="btn btn-primary">Показать</button>
</form>

<hr>

<table class="table table-striped">
    <thead>
        <tr>
            <th>#</th>
            <th>Автор</th>
            <th>Количество книг</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($authors as $index => $author): ?>
            <tr>
                <td><?= $index + 1 ?></td>
                <td><?= Html::encode($author['full_name']) ?></td>
                <td><?= $author['book_count'] ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
