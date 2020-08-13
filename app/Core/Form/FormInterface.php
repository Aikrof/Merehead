<?php
/**
 * @link https://github.com/Aikrof
 * @package App\Core\Form
 * @author Denys <AikrofStark@gmail.com>
 */

declare(strict_types = 1);

namespace App\Core\Form;

/**
 * Interface FormInterface
 */
interface FormInterface
{
    /**
     * Get the validation rules.
     *
     * @return array
     */
    public function rules(): array;
}