<?php
namespace App\Models;
use DB;
use Illuminate\Database\Eloquent\Model;

class Locality extends Model{
    protected $table = 'locality';

    protected $guarded = ['id'];








    public function cityState(){
        return $this->belongsTo('App\Models\State', 'state_id');
    }

     public function cityCountry(){
        return $this->belongsTo('App\Models\Country', 'country_id');
    }

     public function cityLocality(){
        return $this->belongsTo('App\Models\Locality', 'city_id');
    }

}