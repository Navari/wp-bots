<?php


namespace App\Bot\Http;


use Psr\Http\Message\ResponseInterface;

class Response
{
    public int $code;
    public string $raw_body;
    /**
     * @var mixed
     */
    public $body;
    public array $headers;

    /**
     * Response constructor.
     * @param ResponseInterface $response
     * @throws \JsonException
     */
    public function __construct(ResponseInterface $response)
    {
        $this->code     = $response->getStatusCode();
        $this->headers  = $response->getHeaders();
        $raw_body       = $response->getBody()->getContents();
        $this->raw_body = $raw_body;
        $this->body     = $raw_body;

        if (function_exists('json_decode') && is_object(json_decode($raw_body))) {
            $json = json_decode($raw_body, false, 512, JSON_THROW_ON_ERROR | JSON_BIGINT_AS_STRING);

            if (json_last_error() === JSON_ERROR_NONE) {
                $this->body = $json;
            }
        }
    }

}
