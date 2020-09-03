<?php
/**
 * @link https://github.com/Aikrof
 * @package App\Entities
 * @author Denys <AikrofStark@gmail.com>
 */

declare(strict_types = 1);

namespace App\Entities;

use App\Core\Entity\BaseEntity;

/**
 * Class BookEntity
 *
 * @var string $uid
 */
class BookEntity extends BaseEntity
{
    /**
     * @var UserEntity
     */
    protected $user;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var int
     */
    protected $n_pages;

    /**
     * @var string
     */
    protected $annotation;

    /**
     * @var string
     */
    protected $img;

    /**
     * @var AuthorEntity
     */
    protected $author;

    /**
     * @return UserEntity
     */
    public function getUser(): UserEntity
    {
        return $this->user;
    }

    /**
     * @param UserEntity $user
     *
     * @return static
     */
    public function setUser(UserEntity $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return static
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return int
     */
    public function getNPages(): int
    {
        return $this->n_pages;
    }

    /**
     * @param int $n_pages
     *
     * @return static
     */
    public function setNPages(int $n_pages): self
    {
        $this->n_pages = $n_pages;

        return $this;
    }

    /**
     * @return string
     */
    public function getAnnotation(): string
    {
        return $this->annotation;
    }

    /**
     * @param string $annotation
     *
     * @return static
     */
    public function setAnnotation(string $annotation): self
    {
        $this->annotation = $annotation;

        return $this;
    }

    /**
     * @return string
     */
    public function getImg(): string
    {
        return $this->img;
    }

    /**
     * @param string $img
     *
     * @return static
     */
    public function setImg(string $img): self
    {
        $this->img = $img;

        return $this;
    }

    /**
     * @return AuthorEntity
     */
    public function getAuthor(): AuthorEntity
    {
        return $this->author;
    }

    /**
     * @param AuthorEntity $author
     *
     * @return static
     */
    public function setAuthor(AuthorEntity $author): self
    {
        $this->author = $author;

        return $this;
    }
}
