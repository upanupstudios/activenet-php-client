<?php

namespace Upanupstudios\Activenet\Php\Client;

final class Config
{
  private $organizationId;
  private $apiKey;
  private $secret;

  public function __construct(string $organizationId, string $apiKey, string $secret)
  {
    $this->organizationId = $organizationId;
    $this->apiKey = $apiKey;
    $this->secret = $secret;
  }

  /**
   * Get Organization ID.
   */
  public function getOrganizationId(): string
  {
      return $this->organizationId;
  }

  /**
   * Get API Key.
   */
  public function getApiKey(): string
  {
      return $this->apiKey;
  }

  /**
   * Get Secret.
   */
  public function getSecret(): string
  {
      return $this->secret;
  }
}