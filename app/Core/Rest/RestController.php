<?php
/**
 * @link https://github.com/Aikrof
 * @package App\Core\Rest
 * @author Denys <AikrofStark@gmail.com>
 */

declare(strict_types = 1);

namespace App\Core\Rest;

use Aikrof\Hydrator\Hydrator;
use Illuminate\Http\Request;
use JsonSerializable;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\JsonResponse;

/**
 * Class RestController
 */
abstract class RestController extends BaseController
{
    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * RestController constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * {@inheritDoc}
     *
     * @param string $method
     * @param array $parameters
     *
     * @return JsonResponse
     */
    public function callAction($method, $parameters): JsonResponse
    {
        $content = parent::callAction($method, $parameters);

        return $this->prepareContent($content);
    }

    /**
     * @param mixed $content
     *
     * @return JsonResponse
     */
    protected function prepareContent($content): JsonResponse
    {
        $responseData = !empty($content) ? $content : null;

        if (\is_object($content) === true) {
            $responseData = ($content instanceof JsonSerializable)
                ? $content
                : Hydrator::extract($content, [], true);
        }

        return $this->toJson($responseData);
    }

    /**
     * Create json
     *
     * @param mixed $data
     *
     * @return JsonResponse
     */
    protected function toJson($data): JsonResponse
    {
        return response()->json($data);
    }
}
