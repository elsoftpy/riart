<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class beneficios_categoria extends Model
{
    protected $table = "beneficios_categorias";

    protected $fillable = ["titulo", "descripcion", "file_path", "file_name"];

    public function item(){
    	return $this->hasMany('App\beneficios_item', "categoria_id");
    }
}
