<?php
/**
 * @link https://github.com/Aikrof
 * @package App\Core\Form
 * @author Denys <AikrofStark@gmail.com>
 */

declare(strict_types = 1);

namespace App\Core\Form;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Support\MessageBag;

/**
 * Class BaseForm
 */
abstract class BaseForm extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get data from form
     *
     * @return array
     */
    public function getData(): array
    {
        return $this->validated();
    }

    /**
     * {@inheritDoc}
     *
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator): void
    {
        $this->sendException($validator->messages());
    }

    /**
     * Send validation exception.
     *
     * @param MessageBag $message
     */
    protected function sendException(MessageBag $message): void
    {
        $error = [
            "message" => "The given data was invalid.",
            "error" => $message,
        ];

        throw new HttpResponseException(response()->json($error, 422));
    }
}