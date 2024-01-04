<?php

namespace Upanupstudios\Activenet\Php\Client;

/**
 * General abstract class.
 */
class General extends AbstractApi {

  /**
   * Returns a list of sites for your organization.
   */
  public function getSites($params = []) {
    $response = $this->client->request('GET', 'sites');

    $response = $this->processResponse($response);

    return $response;
  }

  /**
   * Returns a list of centers for your organization.
   */
  public function getCenters($params = []) {
    $response = $this->client->request('GET', 'centers');

    $response = $this->processResponse($response);

    return $response;
  }

}
