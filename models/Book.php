<?php

namespace app\models;

/**
 * This is the model class for table "book".
 *
 * @property int $id
 * @property string $title
 * @property int $publication_year
 * @property string|null $description
 * @property string $isbn
 * @property string|null $image_url
 *
 * @property Author[] $authors
 */
class Book extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'book';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description', 'image_url'], 'default', 'value' => null],
            [['title', 'publication_year', 'isbn'], 'required'],
            [['publication_year'], 'integer'],
            [['description'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['isbn'], 'string', 'max' => 32],
            [['image_url'], 'string', 'max' => 2048],
            [['isbn'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'publication_year' => 'Publication Year',
            'description' => 'Description',
            'isbn' => 'Isbn',
            'image_url' => 'Image Url',
        ];
    }

    /**
     * Gets query for [[Authors]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthors()
    {
        return $this->hasMany(Author::class, ['id' => 'author_id'])->viaTable('book_author', ['book_id' => 'id']);
    }
}
