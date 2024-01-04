<?php

namespace Upanupstudios\Activenet\Php\Client;

/**
 * AbstractApi abstract class.
 */
abstract class AbstractApi {
  /**
   * The Activenet class.
   *
   * @var Activenet
   */
  protected $client;

  /**
   * {@inheritdoc}
   */
  public function __construct(Activenet $client) {
    $this->client = $client;
  }

  /**
   * {@inheritdoc}
   */
  protected function processResponse($response) {
    $body = [];

    if (!empty($response) && is_array($response)) {
      if (!empty($response['headers']) && $response['headers']['response_code'] == '0000') {
        $body = $response['body'];

        $response = $body;
      }
    }

    // @todo What is this response object if it's empty and not an array?
    return $response;
  }

}
