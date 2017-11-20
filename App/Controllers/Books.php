<?php

namespace App\Controllers;

use App\Entities\Book;
use PhpBoot\Application;
use PhpBoot\DB\DB;
use PhpBoot\DI\Traits\EnableDIAnnotations;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Library management
 *
 * Here's an example of how to use the PhpBoot framework by implementing a simple set of library management interfaces.
 *
 * @path /books
 */
class Books
{
    use EnableDIAnnotations; //Enable injecting dependencies through @inject tags

    /**
     * @param LoggerInterface $logger Incoming through dependency injection
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger;
    }

    /**
     * Find books
     *
     * @route GET /
     *
     * @param string $name Find the title
     * @param int $offset Result set offset {@v min:0}
     * @param int $limit Returns the maximum number of results {@v max:1000}
     * @param int $total The total number
     * @throws BadRequestHttpException Parameter error
     * @return Book[] Book list
     */
    public function findBooks($name, &$total, $offset = 0, $limit = 100)
    {
        $query = \PhpBoot\models($this->db, Book::class)
            ->findWhere(['name' => ['LIKE' => "%$name%"]]);
        $total = $query->count();
        return $query->limit($offset, $limit)->get();
    }

    /**
     * Get a book
     *
     * Get the specified book information
     *
     * @route GET /{id}
     *
     * @param string $id Specify the book number
     * @hook \App\Hooks\BasicAuth
     * @throws NotFoundHttpException Book does not exist
     * @return Book Book information
     */
    public function getBook($id)
    {
        $book = \PhpBoot\models($this->db, Book::class)
            ->find($id) or \PhpBoot\abort(new NotFoundHttpException("book $id not found"));
        return $book;
    }

    /**
     * New book
     *
     * Create a new book based on the specified information
     *
     * @route POST /
     * @param Book $book {@bind request.request} Here's the content of the post bound to the book parameter
     * @throws BadRequestHttpException
     * @return string Return the number of the new book
     */
    public function createBook(Book $book)
    {
        !$book->id or \PhpBoot\abort(new BadRequestHttpException("should not specify id while creating books"));
        $this->logger->info("attempt to create book: " . json_encode($book));

        \PhpBoot\model($this->db, $book)->create();

        $this->logger->info("create book {$book->id} OK");
        return $book->id;
    }

    /**
     * Modify the book
     *
     * Modify the book based on the specified information
     *
     * @route PUT /
     * @param Book $book {@bind request.request} Here's the content of the post bound to the book parameter
     * @throws BadRequestHttpException
     * @return void success
     */
    public function updateBook(Book $book)
    {
        $book->id or \PhpBoot\abort(new BadRequestHttpException("update {$book->id} failed"));
        $this->logger->info("attempt to update book: " . json_encode($book));

        \PhpBoot\model($this->db, $book)->update();

        $this->logger->info("update book {$book->id} OK");
    }

    /**
     * Delete book
     * Delete the specified book
     *
     * @route DELETE /{id}
     * @param string $id
     * @throws NotFoundHttpException Specified book does not exist
     * @return void
     */
    public function deleteBook($id)
    {
        $this->logger->info("attempt to delete $id");

        \PhpBoot\models($this->db, Book::class)->delete($id) or \PhpBoot\abort(new NotFoundHttpException("book $id not found"));

        $this->logger->info("delete book $id OK");
    }


    /**
     * @inject
     * @var LoggerInterface
     */
    public $logger;

    /**
     * @inject
     * @var DB
     */
    private $db;
}