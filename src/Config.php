<?php

namespace Upanupstudios\Activenet\Php\Client;

/**
 * Config base class.
 */
final class Config {

  /**
   * The Organization ID.
   *
   * @var string
   */
  private $organizationId;

  /**
   * The API key.
   *
   * @var string
   */
  private $apiKey;

  /**
   * The Secret token.
   *
   * @var string
   */
  private $secret;

  /**
   * {@inheritdoc}
   */
  public function __construct(string $organizationId, string $apiKey, string $secret) {
    $this->organizationId = $organizationId;
    $this->apiKey = $apiKey;
    $this->secret = $secret;
  }

  /**
   * Get Organization ID.
   */
  public function getOrganizationId(): string {
    return $this->organizationId;
  }

  /**
   * Get API key.
   */
  public function getApiKey(): string {
    return $this->apiKey;
  }

  /**
   * Get Secret token.
   */
  public function getSecret(): string {
    return $this->secret;
  }

}
