<?php

namespace Sympla\RemoteAuthenticationMiddleware;

use GuzzleHttp\ClientInterface;
use Sympla\RemoteAuthentication\Exception\InvalidCredentialsException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Interop\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Sympla\RemoteAuthentication\OAuth2RemoteAuthentication;

/**
 * Class OAuth2ClientCredentialsAuthentication
 * @package Authentication
 */
class OAuth2RemoteAuthenticationMiddleware
{
    /** @var ContainerInterface */
    private $container = null;
    /** @var array */
    private $options = [];
    /** @var ClientInterface */
    private $httpClient;

    public function __construct(ClientInterface $httpClient, ContainerInterface $container, array $options = [])
    {
        $this->container = $container;
        $this->options = $options;
        $this->httpClient = $httpClient;
    }

    public function __invoke(
        RequestInterface $request,
        ResponseInterface $response,
        callable $next
    ) {
        //No paths protected, everything is public. Do not authenticate.
        if (!isset($this->options["protected"])) {
            return $next($request, $response);
        }

        $uri = $request->getUri();
        $protected = false;

        foreach ($this->options["protected"] as $routePattern) {
            if (preg_match($routePattern, $uri)) {
                $protected = true;
                break;
            }
        }

        // Route ain't protected. No need to authenticate.
        if (!$protected) {
            return $next($request, $response);
        }

        $token = $request->getHeaderLine('Authorization');

        try {
            $authenticator = new OAuth2RemoteAuthentication($this->httpClient, $this->options['authentication_server']);
            $user = $authenticator->getUserFromRequest($request);

            $this->container['user'] = $user;

            return $next($request, $response);
        } catch (InvalidCredentialsException $e) {
            $response
                ->getBody()
                ->write($e->getMessage() . "\n");

            return $response->withStatus(401);
        }
    }
}
