<?php
/**
 * @link https://github.com/Aikrof
 * @package App\Commands\Book
 * @author Denys <AikrofStark@gmail.com>
 */

declare(strict_types=1);

namespace App\Commands\Book;

use App\Commands\CommandInterface;
use App\Core\Form\FormInterface;
use App\Entities\BookEntity;
use App\Repositories\Interfaces\AuthorRepositoryInterface;
use App\Repositories\Interfaces\BookRepositoryInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CreateCommand implements CommandInterface
{
    /** @var array */
    protected $form;

    /**  @var BookEntity */
    protected $entity;

    /** @var BookRepositoryInterface */
    protected $bookRepository;

    /** @var AuthorRepositoryInterface */
    protected $authorRepository;

    /**
     * CreateCommand constructor.
     *
     * @param array                     $form
     * @param BookEntity                $entity
     * @param BookRepositoryInterface   $bookRepository
     * @param AuthorRepositoryInterface $authorRepository
     */
    public function __construct(
        array $form,
        BookEntity $entity,
        BookRepositoryInterface $bookRepository,
        AuthorRepositoryInterface $authorRepository
    ) {
        $this->form = $form;
        $this->entity = $entity;
        $this->bookRepository = $bookRepository;
        $this->authorRepository = $authorRepository;
    }

    /**
     * {@inheritDoc}
     */
    public function execute(): void
    {
        $imgName = $this->saveFormImg($this->form['img']);
        $this->form['img'] = $imgName;

        $this->entity->hydrateData($this->form);
        $this->entity->generateUid();
        $this->entity->setDate();

        $this->entity->getAuthor()->generateUid();
        $this->entity->getAuthor()->setDate();

        $this->entity->setUser(\Auth::user());

        if (!$this->authorRepository->save($this->entity->getAuthor()) ||
            !$this->bookRepository->saveBook($this->entity)
        ) {
            throw new \RuntimeException('Cannot save user data.');
        }
    }

    /**
     * Save img to storage
     *
     * @param string $base64Img
     *
     * @return string
     */
    protected function saveFormImg(string $base64Img): string
    {
        $type = explode(';', $base64Img)[0];
        $type = explode('/', $type)[1];

        $imageName = Str::random(10) . ".{$type}";

        $data = substr($base64Img, strpos($base64Img, ',') + 1);
        $data = base64_decode($data);


        Storage::disk('local')->put($imageName, $data);

        return $imageName;
    }
}
