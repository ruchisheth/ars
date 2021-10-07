<?php 
namespace App\Services\Import;
use  Maatwebsite\Excel\Collections;
use Illuminate\Support\Facades\Config;


// It's important to extend the Validator class
// By doing that we make sure we keep all the other rules as well!
// Now if you want to override a rule you can do that here as well
// For now we will only focus on creating rules
class CustomImport extends \Maatwebsite\Excel\Collections\ExcelCollection {

    // public function __construct(array $items = array())
    // {
    //     $this->setItems($items);
    // }

    public function setItems($items)
    {
        foreach ($items as $name => $value)
        {

            $value = !empty($value) || is_numeric($value) ? $value : 'test';
            //$value = !empty($value) || is_numeric($value) ? $value : $config = Config::get('excel.import.empty_cell');

            if ($name)
            {
                $this->put($name, $value);
            }
            else
            {
                $this->push($value);
            }
        }
    }

}