<?php
namespace App\Models;
use DB;
use Illuminate\Database\Eloquent\Model;

class Country extends Model{
    protected $table = 'countries';

    protected $guarded = ['id'];

    public $timestamps = false;

    protected $fillable = 
    [
        
        'name',
        
    ];


    public function state(){
    	return $this->hasMany('App\Models\State');
    }

}