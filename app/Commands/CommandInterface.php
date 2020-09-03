<?php
/**
 * @link https://github.com/Aikrof
 * @package App\Commands
 * @author Denys <AikrofStark@gmail.com>
 */

declare(strict_types = 1);


namespace App\Commands;

/**
 * Interface CommandInterface
 */
interface CommandInterface
{
    /**
     * Command logic
     *
     * @return void
     */
    public function execute(): void;
}