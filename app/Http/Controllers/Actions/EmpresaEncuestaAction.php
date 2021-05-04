<?php

namespace App\Http\Controllers\Actions;

use App\Cabecera_encuesta;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EmpresaEncuestaAction extends Controller
{
    public function getEncuesta($empresa)
    {
        return Cabecera_encuesta::where('empresa_id', $empresa)
                                ->pluck('periodo', 'id');
    }
}
