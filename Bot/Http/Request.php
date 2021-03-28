<?php


namespace Navari\Bot\Http;


use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use GuzzleHttp\Psr7\Request as Psr7Request;
use GuzzleHttp\Psr7\Uri;
use GuzzleHttp\Psr7\Utils;

class Request
{
    /**
     * @var ClientInterface
     */
    private static ClientInterface $client;

    public static function setHttpClient(ClientInterface $client): void
    {
        self::$client = $client;
    }

    /**
     * Send a cURL request
     * @param string $method HTTP method to use
     * @param string|Uri $uri URL to send the request to
     * @param array $headers additional headers to send
     *
     * @param mixed $body request body
     * @return Response
     * @throws ClientExceptionInterface
     * @throws \JsonException
     */
    private static function send(string $method, Uri|string $uri, array $headers = [], array $body = null): Response
    {
        if ($body !== null) {
            $body = http_build_query($body);
            $headers['Content-Type'] = 'application/x-www-form-urlencoded';
            $body = Utils::streamFor($body);
        }
        $request = new Psr7Request($method, $uri, $headers, $body);

        return new Response(self::$client->sendRequest($request));
    }

    /**
     * Send a GET request to a URL
     *
     * @param string $url URL to send the GET request to
     * @param array $headers additional headers to send
     * @param array|null $parameters parameters to send in the querystring
     * @return Response
     * @throws ClientExceptionInterface|\JsonException
     */
    public static function get(string $url, array $headers = [], array $parameters = null): Response
    {
        $uri = new Uri($url);
        if ($parameters !== null) {
            $uri = $uri->withQuery(http_build_query($parameters));
        }
        return self::send('GET', $uri, $headers);
    }

    /**
     * @param string $url
     * @param array $headers
     * @param array|null $body
     * @return Response
     * @throws ClientExceptionInterface|\JsonException
     */
    public static function post(string $url, array $headers = [], array $body = null): Response
    {
        return self::send('POST', $url, $headers, $body);
    }

}
