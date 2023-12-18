<?php

namespace Upanupstudios\Activenet\Php\Client;

/**
 * Activity abstract class.
 */
class Activity extends AbstractApi {

  /**
   * Total records per page.
   *
   * @var int
   */
  protected $total_records_per_page = 500;

  /**
   * Response body.
   *
   * @var array
   */
  protected $body = [];

  /**
   * Returns a list of activities for your request parameters.
   */
  public function GetActivities($query = [], $page_info = []) {
    // Default page info.
    $default_page_info = [
    // @todo Add object parameter
      'total_records_per_page' => $this->total_records_per_page,
      'page_number' => 1,
    ];

    if (!empty($page_info)) {
      $page_info = array_merge($default_page_info, $page_info);
    }
    else {
      $page_info = $default_page_info;
    }

    $response = $this->client->request('GET', 'activities', [
      'query' => $query,
      'headers' => ['page_info' => json_encode($page_info)],
    ]);

    if (!empty($response) && is_array($response)) {
      if (!empty($response['headers']) && $response['headers']['response_code'] == '0000') {
        // Save the body.
        $this->body = array_merge($this->body, $response['body']);

        if (!empty($response['headers']['page_info']['total_page']) && $response['headers']['page_info']['page_number'] < $response['headers']['page_info']['total_page']) {
          // Increase page number.
          $page_info = ['page_number' => $response['headers']['page_info']['page_number'] + 1];

          // Call activities again (recursive) on the second page.
          // @todo Is there a way to exit out and request again?
          $this->GetActivities($query, $page_info);
        }

        $response = $this->body;
      }
      else {
        $response = $this->body;
        // @todo Response code is not 0! Call again? repeat? provide error?
      }
    }

    return $response;
  }

  /**
   * Returns details of an activity.
   */
  public function GetActivityDetail($activity_id) {
    $response = $this->client->request('GET', 'activities/' . $activity_id);

    $response = $this->processResponse($response);

    return $response;
  }

  /**
   * Returns fees of an activity.
   */
  public function GetActivityFees($activity_id) {
    $response = $this->client->request('GET', 'activities/' . $activity_id . '/fees');

    $response = $this->processResponse($response);

    return $response;
  }

  /**
   * Returns a list of activity types.
   */
  public function GetActivityTypes($params = []) {
    $response = $this->client->request('GET', 'activitytypes');

    $response = $this->processResponse($response);

    return $response;
  }

  /**
   * Returns a list of activity categories for your organization.
   */
  public function GetActivityCategories($params = []) {
    $response = $this->client->request('GET', 'activitycategories');

    $response = $this->processResponse($response);

    return $response;
  }

  /**
   * Returns a list of activity other categories for your organization.
   */
  public function GetActivityOtherCategories($params = []) {
    $response = $this->client->request('GET', 'activityothercategories');

    $response = $this->processResponse($response);

    return $response;
  }

}
