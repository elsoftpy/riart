<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class cargos_rubro extends Model
{
    protected $table = "cargos_rubros";

    protected $fillable = ["cargo_id", "rubro_id"];

    public function rubro(){
    	return $this->belongsTo("App\Rubro");
    }

    public function cargo(){
    	return $this->belongsTo("App\Cargo");
    }



}
