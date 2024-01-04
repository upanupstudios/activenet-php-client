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
  protected $totalRecordsPerPage = 500;

  /**
   * Response body.
   *
   * @var array
   */
  protected $body = [];

  /**
   * Returns a list of activities for your request parameters.
   */
  public function getActivities($query = [], $page_info = []) {
    // Default page info.
    $default_page_info = [
    // @todo Add object parameter
      'total_records_per_page' => $this->totalRecordsPerPage,
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
          return $this->GetActivities($query, $page_info);
        }

        // Return if we have all the records.
        if ($response['headers']['page_info']['total_records'] == count($this->body)) {
          return $this->body;
        }
      }
    }

    return $response;
  }

  /**
   * Returns details of an activity.
   */
  public function getActivityDetail($activity_id) {
    $response = $this->client->request('GET', 'activities/' . $activity_id);

    $response = $this->processResponse($response);

    return $response;
  }

  /**
   * Returns fees of an activity.
   */
  public function getActivityFees($activity_id) {
    $response = $this->client->request('GET', 'activities/' . $activity_id . '/fees');

    $response = $this->processResponse($response);

    return $response;
  }

  /**
   * Returns a list of activity types.
   */
  public function getActivityTypes($params = []) {
    $response = $this->client->request('GET', 'activitytypes');

    $response = $this->processResponse($response);

    return $response;
  }

  /**
   * Returns a list of activity categories for your organization.
   */
  public function getActivityCategories($params = []) {
    $response = $this->client->request('GET', 'activitycategories');

    $response = $this->processResponse($response);

    return $response;
  }

  /**
   * Returns a list of activity other categories for your organization.
   */
  public function getActivityOtherCategories($params = []) {
    $response = $this->client->request('GET', 'activityothercategories');

    $response = $this->processResponse($response);

    return $response;
  }

}
