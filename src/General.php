<?php

namespace Upanupstudios\Activenet\Php\Client;

class General extends AbstractApi
{
  protected $total_records_per_page = 500;

  protected $body = [];

  /**
   * Returns a list of sites for your organization
   */
  public function GetSites($params = [])
  {
    $response = $this->client->request('GET', 'sites');

    $response = $this->processResponse($response);

    return $response;
  }

  /**
   * Returns a list of centers for your organization
   */
  public function GetCenters($params = [])
  {
    $response = $this->client->request('GET', 'centers');

    $response = $this->processResponse($response);

    return $response;
  }
}
