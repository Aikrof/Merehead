<?php
/**
 * @link https://github.com/Aikrof
 * @package App\Entities\Core
 * @author Denys <AikrofStark@gmail.com>
 */

declare(strict_types = 1);

namespace App\Core\Entity;

/**
 * Trait DateTrait
 */
trait DateTrait
{
    /**
     * @var float
     */
    protected $dateCreated;

    /**
     * @var float
     */
    protected $dateUpdated;

    /**
     * Get current time.
     *
     * @param float|null $time
     *
     * @return float
     */
    public static function getCurrentTime(float $time = null): float
    {
        $time = $time ?: \microtime(true);

        return (float) $time;
    }

    /**
     * Convert timestamp in to date
     *
     * @param float $time
     * @param string|null $format
     *
     * @return string
     */
    public static function getDateFromTimestamp(float $time, string $format = null): string
    {
        $format = $format ?: 'd-F-Y H:i';

        $date = new \DateTime();
        $date->setTimestamp((int) $time);

        return $date->format($format);
    }

    /**
     * Set date created and updated.
     *
     * @return static
     */
    public function setDate(): self
    {
        $time = self::getCurrentTime();
        $this->setDateCreated($time);
        $this->setDateUpdated($time);

        return $this;
    }


    /**
     * @return float
     */
    public function getDateCreated(): ?float
    {
        return $this->dateCreated;
    }

    /**
     * @param float $dateCreated
     *
     * @return static
     */
    public function setDateCreated(float $dateCreated = null): self
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    /**
     * @return float
     */
    public function getDateUpdated(): ?float
    {
        return $this->dateUpdated;
    }

    /**
     * @param float $dateUpdated
     *
     * @return static
     */
    public function setDateUpdated(float $dateUpdated = null): self
    {
        $this->dateUpdated = $dateUpdated;

        return $this;
    }


}