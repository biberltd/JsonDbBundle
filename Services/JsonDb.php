<?php
namespace BiberLtd\Bundle\JsonDbBundle\Services;

use BiberLtd\Bundle\JsonDbBundle\Services\JsonTable;

/*
	Example:

		$db = new JsonDB( "./path_to_my_jsonfiles/" );
		$result = $db -> select( "json_file_name_without_extension", "search-key", "search-value" );

			Example JSON-File:
				[
					{"ID": "0", "Name": "Hans Wurst", "Age": "12"},
					{"ID": "1", "Name": "Karl Stoascheissa", "Age": "15"},
					{"ID": "2", "Name": "Poidl Peidlbecka", "Age": "14"}
				]

		Method Overview:

			JsonDB -> select ( "table", "key", "value" ) - Selects multible lines which contains the key/value and returns it as array
			JsonDB -> selectAll ( "table" )  - Returns the entire file as array
			JsonDB -> update ( "table", "key", "value", ARRAY ) - Replaces the line which corresponds to the key/value with the array-data
			JsonDB -> updateAll ( "table", ARRAY ) - Replaces the entire file with the array-data
			JsonDB -> insert ( "table", ARRAY ) - Appends a row, returns true on success
			JsonDB -> delete ( "table", "key", "value" ) - Deletes all lines which corresponds to the key/value, returns number of deleted lines
			JsonDB -> deleteAll ( "table" ) - Deletes the whole data, returns "true" on success
*/

class JsonDb {

    protected $path = "./";
    protected $fileExt = ".json";
    protected $tables = array();

    public function __construct($path) {
        if (is_dir($path)) $this->path = $path;
        else throw new \Exception("JsonDB Error: Path not found");
    }

    protected function getTableInstance($table) {
        if (isset($tables[$table])) return $tables[$table];
        else return $tables[$table] = new JsonTable($this->path.$table);
    }

    public function __call($op, $args) {
        if ($args && method_exists("JsonTable", $op)) {
            $table = $args[0].$this->fileExt;
            return $this->getTableInstance($table)->$op($args);
        } else throw new \Exception("JsonDB Error: Unknown method or wrong arguments ");
    }

    public function setExtension($_fileExt) {
        $this->fileExt = $_fileExt;
        return $this;
    }

}

