<?php

namespace App\Http\Controllers\Actions;

use App\Empresa;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Rubro;

class EmpresaRubroAction extends Controller
{
    public function getEmpresas(Rubro $rubro)
    {
        $rubroId = $rubro->id;

        return Empresa::where('rubro_id', $rubroId)
                      ->pluck('descripcion', 'id');
    }
}
