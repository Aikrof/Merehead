<?php
/**
 * @link https://github.com/Aikrof
 * @package App\Commands\Book
 * @author Denys <AikrofStark@gmail.com>
 */

declare(strict_types = 1);

namespace App\Commands\Book;

/**
 * Class UpdateCommand
 */
class UpdateCommand extends CreateCommand
{
    /**
     * {@inheritDoc}
     */
    public function execute(): void
    {
        if (empty($this->form)) {
            return;
        }

        if (!empty($this->form['img'])) {
            $imgName = $this->saveFormImg($this->form['img']);
            $this->form['img'] = $imgName;
        }

        $data = $this->entity->extractData();
        foreach ($this->form as $key => $value) {
            $data[$key] = $value;
        }

        $this->entity->hydrateData($data);
        $this->entity->setDateUpdated();

        if (isset($data['author']) && $data['author'] !== null) {
            $this->entity->getAuthor()->setDateUpdated();

            if (!$this->authorRepository->update($this->entity->getAuthor())) {
                throw new \RuntimeException('Cannot update author data.');
            }
        }

        if (!$this->bookRepository->updateBook($this->entity)) {
            throw new \RuntimeException('Cannot update book data.');
        }
    }
}
