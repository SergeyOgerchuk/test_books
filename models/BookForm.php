<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Модель класса для CRUD книги
 */
class BookForm extends Model
{

    public $title;
    public $publication_year;
    public $description;
    public $isbn;
    public $image_url;
    public $authorIds = [];
    private Book $book;

    public function __construct(Book $book, $config = [])
    {
        parent::__construct($config);

        $this->book = $book;

        if (!$book->isNewRecord) {
            $this->title = $book->title;
            $this->publication_year = $book->publication_year;
            $this->description = $book->description;
            $this->isbn = $book->isbn;
            $this->image_url = $book->image_url;
            $this->authorIds = ArrayHelper::getColumn($book->authors, 'id');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'publication_year', 'isbn'], 'required'],
            [['publication_year'], 'integer'],
            [['description'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['isbn'], 'string', 'max' => 32],
            [['image_url'], 'string', 'max' => 2048],
            [['image_url'], 'url'],

            [['authorIds'], 'required', 'message' => 'Выберите хотя бы одного автора.'],
            [['authorIds'], 'each', 'rule' => ['integer']],

            [
                ['isbn'],
                'unique',
                'targetClass' => Book::class,
                'targetAttribute' => 'isbn',
                'filter' => function ($query) {
                    if (!$this->book->isNewRecord) {
                        $query->andWhere(['<>', 'id', $this->book->id]);
                    }
                },
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'title' => 'Название',
            'publication_year' => 'Год публикации',
            'description' => 'Описание',
            'isbn' => 'ISBN',
            'image_url' => 'картинка',
            'authorIds' => 'Автор(ы)',
        ];
    }

    public function getBook()
    {
        return $this->book;
    }

    public function save()
    {
        if (!$this->validate()) {
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $this->book->title = $this->title;
            $this->book->publication_year = $this->publication_year;
            $this->book->description = $this->description;
            $this->book->isbn = $this->isbn;
            $this->book->image_url = $this->image_url;

            if (!$this->book->save(false)) {
                throw new \RuntimeException('Не удалось сохранить книгу.');
            }

            Yii::$app->db->createCommand()
                ->delete('book_author', ['book_id' => $this->book->id])
                ->execute();

            $rows = [];

            foreach ($this->authorIds as $authorId) {
                $rows[] = [$this->book->id, $authorId];
            }

            Yii::$app->db->createCommand()
                ->batchInsert(
                    'book_author',
                    ['book_id', 'author_id'],
                    $rows
                )
                ->execute();

            $transaction->commit();

            return true;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            Yii::error($e);

            $this->addError('', 'Не удалось сохранить книгу.');

            return false;
        }
    }

}
