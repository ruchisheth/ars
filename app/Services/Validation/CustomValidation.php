<?php 
namespace App\Services\Validation;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Validator;

// It's important to extend the Validator class
// By doing that we make sure we keep all the other rules as well!
// Now if you want to override a rule you can do that here as well
// For now we will only focus on creating rules
class CustomValidation extends Validator {

    // Laravel keeps a certain convention for rules
    // So the function is called validateGreaterThen    
    // Then the rule is greater_then

    // A validation rule accepts three parameters
    // $attribute This is the name of the input
    // $value This is the value of the input
    // $parameters This is a parameter for the rule, so greater_then:1,2 has two parameters
    // the $parameters are returned as an array so for the first parameter: $parameters[0]

    // Now that we know how a rule works let's create one
    /**
     * $attribute Input name
     * $value Input value
     * $parameters Table, field1
     */


    public function validateUniqueWith($attribute, $value, $parameters)
    {
        $conditions = [];
        $exception = [];

        // $result = DB::table($parameters[0])->where(function($query) use ($attribute, $value, $parameters) {
        //     $query->where($attribute, '=', $value);
        //         ->where($parameters[1], '=', $parameters[2]);
        // })->first();

        //list($connection, $table) = $this->parseTable($parameters[0]);
        list($connection, $table) = parent::parseTable($parameters[0]);

        $column = isset($parameters[2]) ? $parameters[2] : $this->guessColumnForQuery($attribute);

        list($idColumn, $id) = [null, null];

        if (isset($parameters[3])) {
            list($idColumn, $id) = $this->getUniqueId($parameters);

            if (strtolower($id) == 'null') {
                $id = null;
            }

            $exception['attribute'] = $idColumn;
            $exception['value'] = $id;
        }

        $condition = explode(":",$parameters[1]);

        for($i = 0; $i< count($condition); $i++){
            $arr = [];
            $arr = explode("=>",$condition[$i]);
            $conditions[$i]['attribute'] = $arr[0];
            $conditions[$i]['value'] = $arr[1];
        }

        $result = DB::table($table)->where(function($query) use ($attribute, $value, $parameters, $conditions, $exception) {
            $query->where($attribute, '=', $value);
            for($i = 0; $i< count($conditions); $i++){
                $query->where($conditions[$i]['attribute'], "=", $conditions[$i]['value']);
            }
            if(count($exception) != 0)
                $query->where($exception['attribute'], '<>', $exception['value']);
        })->first();


        // Now we check if we have a record
        // If we do have a record we return false and the validation will fail
        // If we can't find a record we return true and the validation will succeed
        return $result ? false : true;
    }

    protected function validateEqualOrAfter($attribute, $value, $parameters)
    {

        $this->requireParameterCount(1, $parameters, 'after');

        if (! is_string($value) && ! is_numeric($value) && ! $value instanceof DateTimeInterface) {
            return false;
        }

        if ($format = $this->getDateFormat($attribute)) {
            return $this->validateAfterWithFormat($format, $value, $parameters);
        }

        if (! $date = $this->getDateTimestamp($parameters[0])) {
            $date = $this->getDateTimestamp($this->getValue($parameters[0]));
        }

        if(!$date){
            if(isset($parameters[1])){
                $date = $this->getDateTimestamp($parameters[1]);       
            }
        }

        return $this->getDateTimestamp($value) >= $date;
    }

    protected function validateEqualOrBefore($attribute, $value, $parameters)
    {
        $this->requireParameterCount(1, $parameters, 'before');

        if (! is_string($value) && ! is_numeric($value) && ! $value instanceof DateTimeInterface) {
            return false;
        }

        if ($format = $this->getDateFormat($attribute)) {
            return $this->validateAfterWithFormat($format, $value, $parameters);
        }

        if (! $date = $this->getDateTimestamp($parameters[0])) {
            $date = $this->getDateTimestamp($this->getValue($parameters[0]));
        }


        if(!$date){
            if(isset($parameters[1])){
                $date = $this->getDateTimestamp($parameters[1]);       
            }
        }

        return $this->getDateTimestamp($value) <= $date;
    }

    protected function getUniqueId($parameters)
    {
        //$idColumn = isset($parameters[3]) ? $parameters[3] : 'id';
        $idColumn = isset($parameters[2]) ? $parameters[2] : 'id';

        return [$idColumn, $parameters[3]];
    }


    public function validateUniqueWithArr($attribute, $value, $parameters)
    {
        $this->requireParameterCount(1, $parameters, 'unique');

        list($connection, $table) = $this->parseTable($parameters[0]);

        // The second parameter position holds the name of the column that needs to
        // be verified as unique. If this parameter isn't specified we will just
        // assume that this column to be verified shares the attribute's name.
        $column = isset($parameters[1])
        ? $parameters[1] : $this->guessColumnForQuery($attribute);

        list($idColumn, $id) = [null, null];
        if (isset($parameters[2])) {
            list($idColumn, $id) = $this->getUniqueIds($parameters);

            if (preg_match('/\[(.*)\]/', $id, $matches)) {
                $id = $this->getValue($matches[1]);
            }

            if (strtolower($id) == 'null') {
                $id = null;
            }

            if (filter_var($id, FILTER_VALIDATE_INT) !== false) {
                $id = intval($id);
            }
        }

        // The presence verifier is responsible for counting rows within this store
        // mechanism which might be a relational database or any other permanent
        // data store like Redis, etc. We will use it to determine uniqueness.
        $verifier = $this->getPresenceVerifier();

        $verifier->setConnection($connection);

        $extra = $this->getExtraUnique($parameters);

        return $verifier->getCount(
            $table, $column, $value, $id, $idColumn, $extra

            ) == 0;
    }

    public function validateNumericWithArr($attribute, $value){
          return is_null($value) || is_numeric($value);
    }

    protected function getExtraUnique($parameters)
    {
        if (isset($parameters[4])) {
            $attributes = array_slice($parameters, 5);
            foreach ($attributes as $key) {
                $parameters[5] = $this->getValue($key);
            }
            return $this->getExtraConditions(array_slice($parameters, 4));
        }

        return [];
    }

}