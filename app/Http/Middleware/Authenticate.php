<?php
/**
 * @link https://github.com/Aikrof
 * @package App\Http\Middleware
 * @author Denys <AikrofStark@gmail.com>
 */

declare(strict_types = 1);

namespace App\Http\Middleware;

use App\Core\Rest\HttpStatusCode;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Contracts\Auth\Middleware\AuthenticatesRequests;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

/**
 * Class Authenticate
 */
class Authenticate implements AuthenticatesRequests
{
    /**
     * The authentication factory instance.
     *
     * @var Auth
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  Auth  $auth
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @throws HttpResponseException
     */
    public function handle($request, $next, ...$guards)
    {
        if (!$this->auth->guard()->check()) {
            $this->unauthenticated();
        }

        return $next($request);
    }

    /**
     * Response for unauthenticated user.
     */
    protected function unauthenticated(): void
    {
        $message = [
            'message' => 'Unauthorized.',
        ];

        throw new HttpResponseException(response()->json($message, HttpStatusCode::UNAUTHORIZED));
    }
}
