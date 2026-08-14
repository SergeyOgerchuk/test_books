<?php

use yii\db\Migration;

class m260814_141810_create_catalog_schema extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%author}}', [
            'id' => $this->primaryKey(),
            'full_name' => $this->string(255)->notNull(),
        ]);

        $this->createTable('{{%book}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'publication_year' => $this->integer()->notNull(),
            'description' => $this->text(),
            'isbn' => $this->string(32)->notNull(),
            'image_url' => $this->string(2048),
        ]);

        $this->createIndex(
            'ux-book-isbn',
            '{{%book}}',
            'isbn',
            true
        );

        $this->createTable('{{%book_author}}', [
            'book_id' => $this->integer()->notNull(),
            'author_id' => $this->integer()->notNull(),
            'PRIMARY KEY ([[book_id]], [[author_id]])',
        ]);

        $this->createIndex(
            'idx-book_author-author_id',
            '{{%book_author}}',
            'author_id'
        );

        $this->addForeignKey(
            'fk-book_author-book_id',
            '{{%book_author}}',
            'book_id',
            '{{%book}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-book_author-author_id',
            '{{%book_author}}',
            'author_id',
            '{{%author}}',
            'id',
            'RESTRICT'
        );

        $this->createTable('{{%subscription}}', [
            'id' => $this->primaryKey(),
            'author_id' => $this->integer()->notNull(),
            'phone' => $this->string(32)->notNull(),
        ]);

        $this->createIndex(
            'ux-subscription-author_id-phone',
            '{{%subscription}}',
            ['author_id', 'phone'],
            true
        );

        $this->addForeignKey(
            'fk-subscription-author_id',
            '{{%subscription}}',
            'author_id',
            '{{%author}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-subscription-author_id',
            '{{%subscription}}'
        );

        $this->dropForeignKey(
            'fk-book_author-author_id',
            '{{%book_author}}'
        );

        $this->dropForeignKey(
            'fk-book_author-book_id',
            '{{%book_author}}'
        );

        $this->dropTable('{{%subscription}}');
        $this->dropTable('{{%book_author}}');
        $this->dropTable('{{%book}}');
        $this->dropTable('{{%author}}');
    }
}
