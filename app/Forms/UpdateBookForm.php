<?php
/**
 * @link https://github.com/Aikrof
 * @package App\Forms
 * @author Denys <AikrofStark@gmail.com>
 */

declare(strict_types=1);

namespace App\Forms;

/**
 * Class UpdateBookForm
 */
class UpdateBookForm extends CreateBookForm
{

    public function rules(): array
    {
        return [
            'name' => [
                'string',
            ],
            'n_pages' => [
                'integer',
                'min:1',
            ],
            'annotation' => [
                'string',
                'between:1, 1000',
            ],
            'img' => [
                function ($key, $value) {
                    $errMessage = "Invalid {$key} type, allowed types: " . \implode('|',self::ALLOWED_IMG_TYPES);

                    try {
                        $type = \preg_replace(
                            '/image\//',
                            '',
                            \mime_content_type($value)
                        );
                    } catch (\Exception $e) {
                        $this->validator->errors()->add($key, $errMessage);
                        return;
                    }

                    if (!\in_array($type, self::ALLOWED_IMG_TYPES, true)) {
                        $this->validator->errors()->add($key, $errMessage);
                    }
                }
            ],
            'author' => ['array'],
            'author.firstName' => [
                'string',
            ],
            'author.lastName' => [
                'string',
            ],
        ];
    }
}
