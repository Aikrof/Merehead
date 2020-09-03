<?php
/**
 * @link https://github.com/Aikrof
 * @package App\Http\Controllers
 * @author Denys <AikrofStark@gmail.com>
 */

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\Rest\HttpStatusCode;
use App\Entities\AuthorEntity;
use App\Entities\BookEntity;
use App\Repositories\Interfaces\AuthorRepositoryInterface;
use App\Repositories\Interfaces\BookRepositoryInterface;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

/**
 * Class AuthorController
 */
class AuthorController extends Controller
{
    /**
     * @var AuthorRepositoryInterface
     */
    private $authorRepository;

    /**
     * @var BookRepositoryInterface
     */
    private $bookRepository;

    /**
     * AuthorController constructor.
     *
     * @param Request                   $request
     * @param AuthorRepositoryInterface $authorRepository
     * @param BookRepositoryInterface   $bookRepository
     */
    public function __construct(
        Request $request,
        AuthorRepositoryInterface $authorRepository,
        BookRepositoryInterface $bookRepository
    ) {
        $this->authorRepository = $authorRepository;
        $this->bookRepository = $bookRepository;

        parent::__construct($request);
    }

    /**
     * @return AuthorEntity[]
     */
    public function index(): array
    {
        return $this->authorRepository->getAll();
    }

    /**
     * Get all books for current author
     *
     * @param string $uid
     * @return BookEntity|null
     */
    public function view(string $uid): ?array
    {
        $entity = $this->getAuthor($uid);

        return $this->bookRepository->getAllAuthorBooks($entity);
    }

    /**
     * @param string $uid
     *
     * @return AuthorEntity
     */
    private function getAuthor(string $uid): AuthorEntity
    {
        /** @var AuthorEntity|null $author */
        $author = $this->authorRepository->findByUid($uid);

        if ($author === null) {
            throw new HttpResponseException(
                response()->json(['message' =>"Author wit Uuid: `{$uid}` does not exist."], HttpStatusCode::NOT_FOUND)
            );
        }

        return $author;
    }
}
