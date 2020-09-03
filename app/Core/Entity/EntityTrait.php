<?php
/**
 * @link https://github.com/Aikrof
 * @package App\Core\Entity
 * @author Denys <AikrofStark@gmail.com>
 */

declare(strict_types=1);


namespace App\Core\Entity;


trait EntityTrait
{
    use DateTrait;

    /**
     * First step to create new Entity
     * Generate uid, date created and date updated
     */
    public function touch(): void
    {
        $this->generateUid();
        $this->setDate();
    }

    /**
     * {@inheritDoc}
     */
    public function jsonSerialize ()
    {
        $extract = $this->hide();

        foreach ($this as $key => $field) {
            if ($field instanceof self) {
                foreach ($field->hide() as $item) {
                    $extract[] = "{$key}.{$item}";
                }
            }
        }

        return $this->extractData($extract,true);
    }
}
