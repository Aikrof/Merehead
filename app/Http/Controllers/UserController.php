<?php
/**
 * @link https://github.com/Aikrof
 * @package App\Http\Controllers
 * @author Denys <AikrofStark@gmail.com>
 */

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Entities\UserEntity;
use App\Entities\BookEntity;
use App\Repositories\Interfaces\BookRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @var BookRepositoryInterface
     */
    private $bookRepository;

    /**
     * UserController constructor.
     *
     * @param Request                   $request
     * @param UserRepositoryInterface   $userRepository
     * @param BookRepositoryInterface   $bookRepository
     */
    public function __construct(
        Request $request,
        UserRepositoryInterface $userRepository,
        BookRepositoryInterface $bookRepository
    ) {
        $this->userRepository = $userRepository;
        $this->bookRepository = $bookRepository;

        parent::__construct($request);
    }

    /**
     * Get current user data.
     *
     * @return UserEntity
     */
    public function index(): UserEntity
    {
        return \Auth::user();
    }

    /**
     * Get user by uid.
     *
     * @param string $uid
     *
     * @return UserEntity
     */
    public function view(string $uid): UserEntity
    {
        return $this->userRepository->findByUid($uid);
    }

    /**
     * Get all books of current user.
     *
     * @return BookEntity[]
     */
    public function bookIndex(): array
    {
        return $this->bookRepository->findAllUserBooks(\Auth::user()->getUid());
    }

    /**
     * Get book by book uid of current user.
     *
     * @param string $uid
     *
     * @return BookEntity
     */
    public function bookView(string $uid): BookEntity
    {
        return $this->bookRepository->findUserBook(\Auth::user()->getUid(), $uid);
    }
}
