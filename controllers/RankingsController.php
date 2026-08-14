<?php

namespace app\controllers;

use Yii;
use yii\db\Query;
use yii\web\Controller;

class RankingsController extends Controller
{
    public function actionIndex()
    {
        $year = (int) Yii::$app->request->get('year', date('Y'));

        $authors = (new Query())
            ->select([
                'author.id',
                'author.full_name',
                'book_count' => 'COUNT(DISTINCT book.id)',
            ])
            ->from('author')
            ->innerJoin('book_author', 'book_author.author_id = author.id')
            ->innerJoin('book', 'book.id = book_author.book_id')
            ->where(['book.publication_year' => $year])
            ->groupBy(['author.id', 'author.full_name'])
            ->orderBy([
                'book_count' => SORT_DESC,
                'author.full_name' => SORT_ASC,
            ])
            ->limit(10)
            ->all();

        return $this->render('index', [
            'year' => $year,
            'authors' => $authors,
        ]);
    }
}
