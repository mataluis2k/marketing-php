<?php

namespace mataluis2k\marapost;

use mataluis2k\marapost\Abstractions\Api;
use mataluis2k\marapost\Abstractions\OperationResult;

/**
 * Class Reports
 * @package mataluis2k\marapost
 */
class Reports
{
    use Api;

    /**
     * Reports constructor.
     * @param int $accountId
     * @param string $authToken
     */
    public function __construct(int $accountId, string $authToken)
    {
        $this->auth_token = $authToken;
        $this->accountId = $accountId;
        $this->resource = 'reports';
    }


    /**
     * Gets the list of reports
     * @param int $page page #. (>= 1)
     * @return OperationResult
     */
    public function get(int $page): OperationResult
    {
        return $this->_get('', ['page' => $page]);
    }

    /**
     * Gets the list of reports
     * @param int $id report ID
     * @return OperationResult
     */
    public function getReport(int $id): OperationResult
    {
        return $this->_get($id);
    }

    /**
     * Gets the list of open reports based on filters and fields provided
     *
     * @param array $fields contact field names to retrieve
     * @param \DateTime|null $from the beginning of date range filter
     * @param \DateTime|null $to the end of the date range filter
     * @param bool|null $unique when true, gets only unique opens
     * @param string|null $email filters by provided email in the contact
     * @param string|null $uid filters by uid
     * @param int|null $per determines how many records per request to receive
     * @param int $page page #. (>= 1)
     * @return OperationResult
     */
    public function getOpens(
        int $page,
        array $fields = [],
        \DateTime $from = null,
        \DateTime $to = null,
        bool $unique = null,
        string $email = null,
        string $uid = null,
        int $per = null
    ): OperationResult
    {
        $params = [
            'fields' => implode(',', $fields),
            'from' => $from === null ? null : $from->format('Y-m-d'),
            'to' => $to === null ? null : $to->format('Y-m-d'),
            'unique' => $unique,
            'email' => $email,
            'uid' => $uid,
            'per' => $per,
            'page' => $page
        ];

        $sanitizedParams = $this->_discardNullAndEmptyValues($params);

        return $this->_get('opens', $sanitizedParams);
    }

    /**
     * Gets a list of click reports
     *
     * @param array $fields plucks these contact fields if they exist
     * @param \DateTime|null $from Start of specific date range filter
     * @param \DateTime|null $to end of date range filter
     * @param bool|null $unique If true, gets unique records
     * @param string|null $email Gets Clicks for specific email
     * @param string|null $uid Gets Clicks for provided uid
     * @param int|null $per Gets the specified number of records
     * @param int $page page #. (>= 1)
     * @return OperationResult
     */
    public function getClicks(
        int $page,
        array $fields = [],
        \DateTime $from = null,
        \DateTime $to = null,
        bool $unique = null,
        string $email = null,
        string $uid = null,
        int $per = null
    ): OperationResult
    {
        $params = [
            'fields' => implode(',', $fields),
            'from' => $from === null ? null : $from->format('Y-m-d'),
            'to' => $to === null ? null : $to->format('Y-m-d'),
            'unique' => $unique,
            'email' => $email,
            'uid' => $uid,
            'per' => $per,
            'page' => $page
        ];

        $sanitizedParams = $this->_discardNullAndEmptyValues($params);

        return $this->_get('clicks', $sanitizedParams);
    }

    /**
     * Gets a list of bounce reports
     *
     * @param array $fields plucks these contact fields if they exist
     * @param \DateTime|null $from Start of specific date range filter
     * @param \DateTime|null $to end of date range filter
     * @param bool|null $unique If true, gets unique records
     * @param string|null $email Gets Bounces for specific email
     * @param string|null $uid Gets Bounces for provided uid
     * @param int|null $per Gets the specified number of records
     * @param int $page page #. (>= 1)
     * @return OperationResult
     */
    public function getBounces(
        int $page,
        array $fields = [],
        \DateTime $from = null,
        \DateTime $to = null,
        bool $unique = null,
        string $email = null,
        string $uid = null,
        string $type = null,
        int $per = null
    ): OperationResult
    {
        $params = [
            'fields' => implode(',', $fields),
            'from' => $from === null ? null : $from->format('Y-m-d'),
            'to' => $to === null ? null : $to->format('Y-m-d'),
            'unique' => $unique,
            'email' => $email,
            'uid' => $uid,
            'type' => $type,
            'per' => $per,
            'page' => $page
        ];

        $sanitizedParams = $this->_discardNullAndEmptyValues($params);

        return $this->_get('bounces', $sanitizedParams);
    }

    /**
     * Gets a list of Unsubscribes with provided filter constraints
     *
     * @param array $fields plucks these contact fields if they exist
     * @param \DateTime|null $from Start of specific date range filter
     * @param \DateTime|null $to end of date range filter
     * @param bool|null $unique If true, gets unique records
     * @param string|null $email Gets Unsubscribes for specific email
     * @param string|null $uid Gets Unsubscribes for provided uid
     * @param int|null $per Gets the specified number of records
     * @param int $page page #. (>= 1)
     * @return OperationResult
     */
    public function getUnsubscribes(
        int $page,
        array $fields = [],
        \DateTime $from = null,
        \DateTime $to = null,
        bool $unique = null,
        string $email = null,
        string $uid = null,
        int $per = null
    ): OperationResult
    {
        $params = [
            'fields' => implode(',', $fields),
            'from' => $from === null ? null : $from->format('Y-m-d'),
            'to' => $to === null ? null : $to->format('Y-m-d'),
            'unique' => $unique,
            'email' => $email,
            'uid' => $uid,
            'per' => $per,
            'page' => $page
        ];

        $sanitizedParams = $this->_discardNullAndEmptyValues($params);

        return $this->_get('unsubscribes', $sanitizedParams);
    }

    /**
     * Gets a list of complaints filtered by provided params
     *
     * @param array $fields plucks these contact fields if they exist
     * @param \DateTime|null $from Start of specific date range filter
     * @param \DateTime|null $to end of date range filter
     * @param bool|null $unique If true, gets unique records
     * @param string|null $email Gets Complaints for specific email
     * @param string|null $uid Gets Complaints for provided uid
     * @param int|null $per Gets the specified number of records
     * @param int $page page #. (>= 1)
     * @return OperationResult
     */
    public function getComplaints(
        int $page,
        array $fields = [],
        \DateTime $from = null,
        \DateTime $to = null,
        bool $unique = null,
        string $email = null,
        string $uid = null,
        int $per = null
    ): OperationResult
    {
        $params = [
            'fields' => implode(',', $fields),
            'from' => $from === null ? null : $from->format('Y-m-d'),
            'to' => $to === null ? null : $to->format('Y-m-d'),
            'unique' => $unique,
            'email' => $email,
            'uid' => $uid,
            'per' => $per,
            'page' => $page
        ];

        $sanitizedParams = $this->_discardNullAndEmptyValues($params);

        return $this->_get('complaints', $sanitizedParams);
    }

    /**
     * Gets a list of Ab Reports
     *
     * @param string $name To get ab_reports with mentioned name
     * @param \DateTime|null $from Beginning of date range filter
     * @param \DateTime|null $to End of date range filter
     * @param int|null $per gets the mentioned number of reports
     * @param int $page page #. (>= 1)
     * @return OperationResult
     */
    public function getAbReports(
        string $name,
        int $page,
        \DateTime $from = null,
        \DateTime $to = null,
        int $per = null
    ): OperationResult
    {
        // resetting resource to make url appropriate
        $this->resource = '';
        $params = [
            'name' => $name,
            'from' => $from === null ? null : $from->format('Y-m-d'),
            'to' => $to === null ? null : $to->format('Y-m-d'),
            'per' => $per,
            'page' => $page
        ];

        $sanitizedParams = $this->_discardNullAndEmptyValues($params);

        return $this->_get('ab_reports', $sanitizedParams);

    }

    /**
     * Gets the list of all Journeys
     *
     * @param int $page page #. (>= 1)
     * @return OperationResult
     */
    public function getJourneys(int $page): OperationResult
    {
        return $this->_get('journeys', ['page' => $page]);
    }

}
