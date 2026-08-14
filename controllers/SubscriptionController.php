<?php

namespace app\controllers;

use app\models\Author;
use app\models\Subscription;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class SubscriptionController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'create' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * @param int $authorId
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionCreate($authorId)
    {
        $author = $this->findAuthor($authorId);
        $model = new Subscription();

        if ($model->load($this->request->post())) {
            $model->author_id = $author->id;

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Вы успешно подписались на автора.');
            } else {
                Yii::$app->session->setFlash(
                    'error',
                    implode(' ', $model->getFirstErrors())
                );
            }
        }

        return $this->redirect(['author/view', 'id' => $author->id]);
    }

    /**
     * @param int $id
     * @return Author
     * @throws NotFoundHttpException
     */
    protected function findAuthor($id)
    {
        if (($model = Author::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Автор не найден');
    }
}
