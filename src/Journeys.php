<?php

namespace mataluis2k\marapost;

use mataluis2k\marapost\ResultTypes\GetResult;
use mataluis2k\marapost\Abstractions\Api;
use mataluis2k\marapost\Abstractions\OperationResult;

/**
 * Class Journeys
 * @package mataluis2k\marapost
 */
class Journeys
{
    use Api;

	public function __construct(int $accountId, string $authToken)
	{
        $this->auth_token = $authToken;
        $this->accountId = $accountId;
        $this->resource = 'journeys';
	}

    /**
     * Gets the list of journeys.
     *
     * @param int $page page #. (>= 1)
     * @return GetResult
     */
	public function get(int $page) : OperationResult
	{
        try {
            return $this->_get('', ['page' => $page]);
        } catch (\Exception $e) {
            die('exception ');
        }
	}

    /**
     * Gets the list of all campaigns for the specified journey.
     *
     * @param int $journeyId
     * @param int $page page #. (>= 1)
     * @return GetResult
     */
	public function getCampaigns(int $journeyId, int $page) : OperationResult
	{
        try {
            return $this->_get($journeyId."/journey_campaigns", ['page' => $page]);
        } catch (\Exception $e) {
            die('exception ');
        }
	}

    /**
     * Gets the list of all contacts for the specified journey.
     *
     * @param int $journeyId
     * @param int $page page #. (>= 1)
     * @return GetResult
     */
	public function getContacts(int $journeyId, int $page) : OperationResult
	{
        try {
            return $this->_get($journeyId."/journey_contacts", ['page' => $page]);
        } catch (\Exception $e) {
            die('exception ');
        }
	}

    /**
     * Stops all journeys, filtered for the matching parameters.
     *
     * @param int $contactId this filter ignored if not greater than 0.
     * @param string $recipientEmail this filter ignored if null.
     * @param string $uid this filter ignored if null.
     * @param int $page page #. (>= 1)
     * @return OperationResult
     */
	public function stopAll(
	    int $contactId,
        string $recipientEmail,
        string $uid,
        int $page
    ) : OperationResult
	{
	    $params = [];
	    if ($contactId > 0) {
	        $params['contact_id'] = $contactId;
        }
	    if ($recipientEmail != null) {
	        $params['email'] = $recipientEmail;
        }
	    if ($uid != null) {
	        $params['uid'] = $uid;
        }
	    $params['page'] = $page;
		$result = $this->_put("stop_all_journeys", $params);
		return $result;
	}

    /**
     * Pause the specified journey for the specified contact.
     *
     * @param int $journeyId
     * @param int $contactId
     * @return OperationResult
     */
	public function pauseJourneyForContact(int $journeyId, int $contactId) : OperationResult
	{
        $result = $this->_put($journeyId."/stop/".$contactId, []);
        return $result;
	}

    /**
     * Pause the specified journey for the contact having the specified UID.
     *
     * @param int $journeyId
     * @param string $uid
     * @return OperationResult
     */
	public function pauseJourneyForUid(int $journeyId, string $uid) : OperationResult
	{
	    $params["uid"] = $uid;
        $result = $this->_put($journeyId."/stop/uid", $params);
        return $result;
	}

    /**
     * Reset the specified journey for the specified active/paused contact. Resetting a contact to the beginning of the
     * journeys will result in sending of the same journey campaigns as originally sent.
     *
     * @param int $journeyId
     * @param int $contactId
     * @return OperationResult
     */
	public function resetJourneyForContact(int $journeyId, int $contactId) : OperationResult
	{
        $result = $this->_put($journeyId."/reset/".$contactId, []);
		return $result;
	}

    /**
     * Reset the specified journey for the active/paused contact having the specified UID. Resetting a contact to the
     * beginning of the journeys will result in sending of the same journey campaigns as originally sent.
     *
     * @param int $journeyId
     * @param string $uid
     * @return OperationResult
     */
	public function resetJourneyForUid(int $journeyId, string $uid) : OperationResult
	{
	    $params["uid"] = $uid;
        $result = $this->_put($journeyId."/reset/uid", $params);
		return $result;
	}

    /**
     * Restarts a journey for a paused contact. Adds a new contact in journey. Retriggers the journey for a contact
     * who has finished its journey once. (To retrigger, MAKE SURE that "Retrigger Journey" option is enabled.)
     *
     * @param int $journeyId
     * @param int $contactId
     * @return OperationResult
     */
	public function startJourneyForContact(int $journeyId, int $contactId) : OperationResult
	{
        $result = $this->_put($journeyId."/start/".$contactId, []);
        return $result;
	}

    /**
     * Restarts a journey for a paused contact having the specified UID. Adds a new contact in journey. Retriggers the
     * journey for a contact who has finished its journey once. (To retrigger, MAKE SURE that "Retrigger Journey"
     * option is enabled.)
     *
     * @param int $journeyId
     * @param string $uid
     * @return OperationResult
     */
	public function startJourneyForUid(int $journeyId, string $uid) : OperationResult
	{
        $params["uid"] = $uid;
        $result = $this->_put($journeyId."/start/uid", $params);
        return $result;
	}
}