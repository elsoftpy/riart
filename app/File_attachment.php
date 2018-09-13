<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File_attachment extends Model
{
    protected $table = "file_attachments";

    protected $fillable = ["filename", "rubro_id", "periodo", "activo"];

    public function rubro(){
    	return $this->belongsTo('App\Rubro');
    }
}
