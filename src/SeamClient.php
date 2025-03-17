<?php

namespace Seam;

use Seam\Utils\PackageVersion;

use GuzzleHttp\Client as HTTPClient;
use \Exception as Exception;
use Seam\HttpApiError;
use Seam\HttpUnauthorizedError;
use Seam\HttpInvalidInputError;
use Seam\Routes\Routes;

define("LTS_VERSION", "1.0.0");

class SeamClient
{
  private Routes $routes;
  public string $api_key;
  public HTTPClient $client;
  public string $ltsVersion = LTS_VERSION;

  public function __construct(
    $api_key = null,
    $endpoint = "https://connect.getseam.com",
    $throw_http_errors = false
  ) {
    $this->api_key = $api_key ?: (getenv("SEAM_API_KEY") ?: null);
    $seam_sdk_version = PackageVersion::get();
    $this->client = new HTTPClient([
      "base_uri" => $endpoint,
      "timeout" => 60.0,
      "headers" => [
        "Authorization" => "Bearer " . $this->api_key,
        "User-Agent" => "Seam PHP Client " . $seam_sdk_version,
        "seam-sdk-name" => "seamapi/php",
        "seam-sdk-version" => $seam_sdk_version,
        "seam-lts-version" => $this->ltsVersion,
      ],
      "http_errors" => $throw_http_errors,
    ]);
    $this->routes = new Routes($this);
  }

  public function __get(string $name): mixed
  {
    return $this->routes->$name;
  }

  public function request(
    $method,
    $path,
    $json = null,
    $query = null,
    $inner_object = null
  ) {
    $options = [
      "json" => $json,
      "query" => $query,
    ];
    $options = array_filter($options, fn($option) => $option !== null);

    // TODO handle request errors
    $response = $this->client->request($method, $path, $options);
    $status_code = $response->getStatusCode();
    $request_id = $response->getHeaderLine("seam-request-id");

    $res_json = null;
    try {
      $res_json = json_decode($response->getBody());
    } catch (Exception $ignoreError) {
    }

    if ($status_code >= 400) {
      if ($status_code === 401) {
        throw new HttpUnauthorizedError($request_id);
      }

      if (($res_json->error ?? null) != null) {
        if ($res_json->error->type === "invalid_input") {
          throw new HttpInvalidInputError(
            $res_json->error,
            $status_code,
            $request_id
          );
        }

        throw new HttpApiError(
          $res_json->error,
          $status_code,
          $request_id
        );
      }

      throw GuzzleHttpExceptionRequestException::create(
        new GuzzleHttpPsr7Request($method, $path),
        $response
      );
    }

    return $inner_object ? $res_json->$inner_object : $res_json;
  }
}
