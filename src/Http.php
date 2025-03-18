<?php

namespace Seam;

use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;
use Seam\HttpApiError;
use Seam\HttpUnauthorizedError;
use Seam\HttpInvalidInputError;

class Http
{
  public static function createClient(array $config): GuzzleHttpClient
  {
    $baseUrl = $config['base_url'];
    $headers = $config['headers'] ?? [];
    $timeout = $config['timeout'] ?? 60.0;

    $handlerStack = HandlerStack::create();
    $handlerStack->push(self::createJsonDecodingMiddleware());
    $handlerStack->push(self::createErrorHandlingMiddleware());

    $clientOptions = [
      'base_uri'    => $baseUrl,
      'timeout'     => $timeout,
      'headers'     => $headers,
      'handler'     => $handlerStack,
      'http_errors' => false,
    ];

    return new GuzzleHttpClient($clientOptions);
  }

  public static function createJsonDecodingMiddleware(): callable
  {
    return function (callable $nextHandler) {
      return function ($request, array $options) use ($nextHandler) {
        return $nextHandler($request, $options)->then(
          function (ResponseInterface $response) {
            $contentType = $response->getHeaderLine('Content-Type');

            if (stripos($contentType, 'application/json') !== 0) {
              return $response;
            }

            $bodyContent = (string) $response->getBody();
            $decodedJson = json_decode($bodyContent, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
              throw new \RuntimeException('Error decoding JSON: ' . json_last_error_msg());
            }

            return $decodedJson;
          }
        );
      };
    };
  }

  public static function createErrorHandlingMiddleware(): callable
  {
    return function (callable $nextHandler) {
      return function ($request, array $options) use ($nextHandler) {
        return $nextHandler($request, $options)->then(
          function (ResponseInterface $response) use ($request) {
            $statusCode  = $response->getStatusCode();
            $requestId   = trim($response->getHeaderLine('seam-request-id'));
            $body        = (string)$response->getBody();
            $decodedBody = json_decode($body);

            if ($statusCode === 401) {
              throw new HttpUnauthorizedError($requestId);
            }

            if (!self::isApiErrorResponse($response, $decodedBody)) {
              if ($statusCode >= 400) {
                throw RequestException::create($request, $response);
              }

              return $response;
            }

            $errorData = $decodedBody->error;
            if (isset($errorData->type) && $errorData->type === 'invalid_input') {
              throw new HttpInvalidInputError($errorData, $statusCode, $requestId);
            }

            throw new HttpApiError($errorData, $statusCode, $requestId);
          }
        );
      };
    };
  }

  public static function isApiErrorResponse(ResponseInterface $response, $decodedBody): bool
  {
    $contentType = $response->getHeaderLine('Content-Type');
    if (stripos($contentType, 'application/json') !== 0) {
      return false;
    }

    if (!is_object($decodedBody) || !isset($decodedBody->error)) {
      return false;
    }

    $error = $decodedBody->error;
    if (!is_object($error)) {
      return false;
    }

    if (!isset($error->type) || !is_string($error->type)) {
      return false;
    }

    if (!isset($error->message) || !is_string($error->message)) {
      return false;
    }

    return true;
  }
}
