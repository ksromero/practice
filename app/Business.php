<?php

namespace App;  
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
   // use Searchable;
    protected $table = 'business';
    /*
    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'businessName' => $this->businessName,
        ];
    }
    public function searchableAs()
    {
        return 'business_index';
    }*/
}
