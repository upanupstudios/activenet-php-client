<?php

namespace Upanupstudios\Activenet\Php\Client;

abstract class AbstractApi
{
  /**
   * @var Activenet
   */
  protected $client;

  public function __construct(Activenet $client)
  {
      $this->client = $client;
  }

  protected function processResponse($response) {
    $body = [];

    if(!empty($response) && is_array($response)) {
      if(!empty($response['headers']) && $response['headers']['response_code'] == '0000') {
        $body = $response['body'];

        $response = $body;
      }
    }
    //TODO: What is this response object if it's empty and not an array?

    return $response;
  }
}
