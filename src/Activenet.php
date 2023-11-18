<?php

namespace Upanupstudios\Activenet\Php\Client;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;

/**
 * ActiveNet Class.
 */
class Activenet {

  /**
   * Config instance.
   *
   * @var Config
   */
  private $config;

  /**
   * ClientInterface instance.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  private $httpClient;

  /**
   * URL of ActiveNet API.
   *
   * @var string
   */
  private $url = 'https://api.amp.active.com/anet-systemapi-ca-sec';

  /**
   * ActiveNet API version.
   *
   * @var string
   */
  private $version = 'v1';

  /**
   * ActiveNet Class constructor.
   */
  public function __construct(Config $config, ClientInterface $httpClient) {
    $this->config = $config;
    $this->httpClient = $httpClient;
  }

  /**
   * Retrieve Config instance.
   */
  public function getConfig(): Config {
    return $this->config;
  }

  /**
   * Retrieve ClientInterface instance.
   */
  public function getHttpClient(): ClientInterface {
    return $this->httpClient;
  }

  /**
   * Retrieve organization info.
   */
  public function organization() {
    try {
      $url = $this->url . '/' . $this->getConfig()->getOrganizationId() . '/api/' . $this->version . '/organization';

      $request = $this->httpClient->request('GET', $url, [
        'headers' => [
          'Accept' => 'application/json',
          'Content-Type' => 'application/json',
        ],
        'query' => [
          'api_key' => $this->getConfig()->getApiKey(),
          'sig' => hash('sha256', $this->getConfig()->getApiKey() . $this->getConfig()->getSecret() . time()),
        ],
      ]);

      // Get body.
      $body = $request->getBody();

      // Decode and return as array.
      $response = json_decode($body->__toString(), TRUE);
    }
    catch (RequestException $exception) {
      $response = $exception->getMessage();
    }

    return $response;
  }

  /**
   * Request data from ActiveNet API.
   *
   * ACTIVE Net System REST API calls are limited to 2 calls per second.
   *
   * If the call rate exceeds 2 calls per second, then the server will return
   * an HTTP 403 status code.
   */
  public function request(string $method, string $uri, array $options = []) {
    $response = [];

    try {
      $url = $this->url . '/' . $this->getConfig()->getOrganizationId() . '/api/' . $this->version . '/' . $uri;

      $headers = [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
      ];

      if (!empty($options['headers']) && is_array($options['headers'])) {
        $headers = array_merge($headers, $options['headers']);
      }

      $query = [
        'api_key' => $this->getConfig()->getApiKey(),
        'sig' => hash('sha256', $this->getConfig()->getApiKey() . $this->getConfig()->getSecret() . time()),
      ];

      if (!empty($options['query']) && is_array($options['query'])) {
        $query = array_merge($query, $options['query']);
      }

      // Start microtime, return in seconds.
      $mtime_start = microtime(TRUE);

      $request = $this->httpClient->request($method, $url, [
        'headers' => $headers,
        'query' => $query,
      ]);

      // Get body.
      $body = $request->getBody();

      // Decode and return as array.
      $response = json_decode($body->__toString(), TRUE);

      // End microtime, return in seconds.
      $mtime_end = microtime(TRUE);

      // Calulate difference in seconds.
      $diff_mtime = $mtime_end - $mtime_start;

      if ($diff_mtime < 0.5) {
        // Sleep until after 0.5 seconds.
        $mtime = (0.5 - $diff_mtime) * 1000000;

        // Add time to make sure it's past the 0.5 seconds.
        $mtime += 100000;

        // Cast to int.
        usleep((int) $mtime);
      }
    }
    catch (RequestException $exception) {
      $response = $exception->getMessage();
    }

    return $response;
  }

  /**
   * Function to return abstract classes.
   *
   * @throws InvalidArgumentException
   */
  public function api(string $name) {
    $api = NULL;

    switch ($name) {
      case 'General':
        $api = new General($this);
        break;

      case 'Activity':
        $api = new Activity($this);
        break;

      default:
        throw new \InvalidArgumentException();
    }

    return $api;
  }

  /**
   * Magic __call method.
   */
  public function __call(string $name, array $args): object {
    try {
      return $this->api($name);
    }
    catch (\InvalidArgumentException $e) {
      throw new \BadMethodCallException("Undefined method called: '$name'.");
    }
  }

}
