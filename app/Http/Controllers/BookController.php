<?php
/**
 * @link https://github.com/Aikrof
 * @package App\Http\Controllers
 * @author Denys <AikrofStark@gmail.com>
 */

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Commands\Book\CreateCommand;
use App\Commands\Book\UpdateCommand;
use App\Core\Rest\HttpStatusCode;
use App\Entities\AuthorEntity;
use App\Entities\BookEntity;
use App\Forms\CreateBookForm;
use App\Forms\UpdateBookForm;
use App\Repositories\Interfaces\AuthorRepositoryInterface;
use App\Repositories\Interfaces\BookRepositoryInterface;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Class BookController
 */
class BookController extends Controller
{
    /**
     * @var BookRepositoryInterface
     */
    private $repository;

    /**
     * BookController constructor.
     *
     * @param Request $request
     * @param BookRepositoryInterface $repository
     */
    public function __construct(
        Request $request,
        BookRepositoryInterface $repository
    ) {
        $this->repository = $repository;

        parent::__construct($request);
    }

    /**
     * @return array
     */
    public function index(): array
    {
        return $this->repository->getAll();
    }

//    /**
//     * @param $authorUid
//     *
//     * @return BookEntity[]
//     */
//    public function view($authorUid): array
//    {
//        $author = $this->getAuthor($authorUid);
//
//        return $this->bookRepository->getAllAuthorBooks($author);
//    }

    /**
     * @param string $uid
     *
     * @return BookEntity
     */
    public function view(string $uid): BookEntity
    {
        return $this->getBook($uid);
    }

    /**
     * Create new book
     *
     * @return BookEntity
     */
    public function create(): BookEntity
    {
        /** @var CreateBookForm $form */
        $form = \App::make(CreateBookForm::class);
        $entity = \App::make(BookEntity::class);

        $command = \App::makeWith(CreateCommand::class, ['form' => $form->getData(), 'entity' => $entity]);
        $command->execute();

        return $entity;
    }

    /**
     * Update exist book
     *
     * @param string $uid
     *
     * @return BookEntity
     */
    public function update(string $uid): BookEntity
    {
        $entity = $this->getBook($uid);

        /** @var UpdateBookForm $form */
        $form = \App::make(UpdateBookForm::class);

        $command = \App::makeWith(UpdateCommand::class, ['form' => $form->getData(), 'entity' => $entity]);
        $command->execute();

        return $entity;
    }

    /**
     * Delete exist book
     *
     * @param string $uid
     *
     * @return void
     */
    public function delete(string $uid): void
    {
        $entity = $this->getBook($uid);

        Storage::disk('local')->delete($entity->getImg());

        $this->authorRepository->delete($entity->getAuthor());
        $this->repository->delete($entity);
    }

    /**
     * @param string $uid
     *
     * @return BookEntity
     */
    private function getBook(string $uid): BookEntity
    {
        /** @var BookEntity|null $author */
        $book = $this->repository->findBook($uid);

        if ($book === null) {
            throw new HttpResponseException(
                response()->json(['message' =>"Book wit Uuid: `{$uid}` does not exist."], HttpStatusCode::NOT_FOUND)
            );
        }

        return $book;
    }
}
