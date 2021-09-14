<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class form_access_key extends Model
{
    use HasFactory;
    
    public static function validate_access_key($key){

        $access_key=self::where('access_key',$key)->first();

        if(!$access_key){
            return -1;
        }//End of if case

       return $access_key->id;

    }//End of function


}//End of class
