<?php
/**
 * @link https://github.com/Aikrof
 * @package App\Forms
 * @author Denys <AikrofStark@gmail.com>
 */

declare(strict_types = 1);

namespace App\Forms;

use App\Core\Form\BaseForm;
use App\Core\Form\FormInterface;

/**
 * Class BookForm
 */
class CreateBookForm extends BaseForm implements FormInterface
{
    public const ALLOWED_IMG_TYPES = ['png', 'jpg', 'jpeg', 'gif'];

    /**
     * {@inheritDoc}
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
            ],
            'n_pages' => [
                'required',
                'integer',
                'min:1',
            ],
            'annotation' => [
                'required',
                'string',
                'between:1, 1000',
            ],
            'img' => [
                'required',
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
            'author' => ['array', 'required'],
            'author.firstName' => [
                'required',
                'string',
            ],
            'author.lastName' => [
                'required',
                'string',
            ],
        ];
    }
}
