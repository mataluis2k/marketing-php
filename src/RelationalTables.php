<?php

namespace mataluis2k\marapost;

use mataluis2k\marapost\Abstractions\Api;
use mataluis2k\marapost\InputTypes\KeyValue;
use mataluis2k\marapost\ResultTypes\GetResult;
use mataluis2k\marapost\Abstractions\OperationResult;

/**
 * Class RelationalTables
 * @package mataluis2k\marapost
 */
class RelationalTables
{
    use Api;

    /**
     * @param int $accountId
     * @param string $authToken
     * @param string $tableName name of the table to act against for
     */
	public function __construct(int $accountId, string $authToken, string $tableName)
	{
		$this->auth_token = $authToken;
		$this->accountId = $accountId;
        $this->resource = $tableName;
	}

	public static function init(int $accountId, string $authToken, string $tableName) : self
    {
        return new RelationalTables($accountId, $authToken, $tableName);
    }

    /**
     * Gets the records of the Relational Table
     * @return GetResult
     */
	public function get() : OperationResult
	{
        return $this->_get("", []);
	}

    /**
     * Gets the specified record from the Relational Table
     *
     * @param string $idFieldName name of the field representing the unique identifier (E.g., "id", "email")
     * @param mixed $idFieldValue value of the identifier field, for the record to get.
     * @return OperationResult
     */
    public function show(string $idFieldName, $idFieldValue) : OperationResult
    {
        $object = (object)array("record" => (object)array($idFieldName => $idFieldValue));
        return $this->_post("show", [], $object);
    }

    /**
     * Adds a record to the Relational Table.
     *
     * @param KeyValue ...$keyValues a list of field name/values for the record to be updated.
     * @return OperationResult
     */
	public function create(KeyValue... $keyValues) : OperationResult
	{
	    $object = new \stdClass();
        $array = [];
	    foreach ($keyValues as $keyValue)
	    {
	        $array[$keyValue->key] = $keyValue->value;
        }
	    $object->record = (object)$array;
	    return $this->_post("create", [], $object);
	}

    /**
     * Updates a record in the Relational Table.
     *
     * @param KeyValue ...$keyValues a list of field name/values for the record to be updated. NOTE: Any DateTime strings
     * must be in one of three formats: "MM/DD/YYYY", "YYYY-MM-DD", or "YYYY-MM-DDThh:mm:ssTZD".
     * @return OperationResult
     */
	public function update(KeyValue... $keyValues) : OperationResult
    {
        $object = new \stdClass();
        $array = [];
        foreach ($keyValues as $keyValue)
        {
            $array[$keyValue->key] = $keyValue->value;
        }
        $object->record = (object)$array;
        return $this->_put("update", [], (object)$keyValues);
    }

    /**
     * Creates or updates a record in the Relational Table.
     *
     * @param KeyValue ...$keyValues a list of field name/values for the record to be created (or updated). NOTE: Any
     * DateTime strings must be in one of three formats: "MM/DD/YYYY", "YYYY-MM-DD", or "YYYY-MM-DDThh:mm:ssTZD".
     * @return OperationResult
     */
    public function upsert(KeyValue... $keyValues) : OperationResult
    {
        $object = new \stdClass();
        $array = [];
        foreach ($keyValues as $keyValue)
        {
            $array[$keyValue->key] = $keyValue->value;
        }
        $object->record = (object)$array;
        return $this->_put("upsert", [], $object);
    }

    /**
     * Deletes the given record of the Relational Table
     *
     * @param string $idFieldName name of the field representing the unique identifier (E.g., "id", "email")
     * @param mixed $idFieldValue value of the identifier field, for the record to delete.
     * @return OperationResult
     */
	public function delete(string $idFieldName, $idFieldValue) : OperationResult
    {
        $object = (object)array("record" => (object)array($idFieldName => $idFieldValue));
        $result = $this->_delete("delete", [], null, $object);
        if (!$result->isSuccess) {
            // first check and ensure the record exists before attempting.
            $showResult = $this->show($idFieldName, $idFieldValue);
            if($showResult->isSuccess && property_exists($showResult->getData()->result, "error")) {
                // it's not *really* an error, the field just doesn't exist.
                $result = $showResult;
                $result->errorMessage = $showResult->getData()->result->error;
            }
        }
        return $result;
    }

    /**
     * @param string|null $overrideResource ignored
     * @return string
     */
    private function url(string $overrideResource = null) : string
    {
        return 'https://rdb.maropost.com/'.$this->accountId.'/'.$this->resource;
    }

    /**
     * Updates/switches which table this service is acting against
     * @param string $newTableName name of the table to use for successive calls.
     */
    public function _setTableName(string $newTableName) { $this->resource = $newTableName; }
    /**
     * @return string name of the table this service is acting against.
     */
    public function _getTableName() { return $this->resource; }

}