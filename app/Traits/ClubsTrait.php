<?php

Namespace App\Traits;

trait ClubsTrait{
    public function club($rubro, $getImagen = null){
        if(app()->getLocale() == "en"){
            switch ($rubro) {
                case 1:
                    $imagen = "images/caratula-bancos.PNG";
                    $club = "Banking Club";
                    break;
                case 2:
                    $imagen = "images/caratula-agro-en.PNG";
                    $club = "Agribusiness Club";
                    break;
                case 3:
                    $imagen = "images/caratula-autos.PNG";
                    $club = 'Car and Machine Club';
                    break;
                case 4:
                    $imagen = "images/caratula-naviera-en.PNG";
                    $club = "Shipping Club";
                    break;
                case 6:
                    $imagen = "images/caratula-bancos.PNG";
                    $club = "Club: Survey of Non-Governmental Organizations";
                    break;
                case 8:
                    $imagen = "images/caratula-bancos.PNG";
                    $club = "Club de Empresas de Consumo Masivo"; 
                    break;
                case 16:
                    $imagen = "images/caratula-bancos.PNG";
                    $club = "AMX Especial"; 
                    break;                   
                default:
                    $imagen = "images/caratula-bancos.PNG";
                    $club = "de Bancos";
                    break;
            }
        }else{
            switch ($rubro) {
                case 1:
                    $imagen = "images/caratula-bancos.PNG";
                    $club = "Club - Bancos de Paraguay";
                    break;
                case 2:
                    $imagen = "images/caratula-agro.PNG";
                    $club = "Club - Empresas de Agronegocios - Paraguay";
                    break;
                case 3:
                    $imagen = "images/caratula-autos.PNG";
                    $club = 'Club - Empresas del Sector Automotriz, Maquinarias y Utilitarios';
                    break;
                case 4:
                    $imagen = "images/caratula-naviera.PNG";
                    $club = "Club - Navieras de Paraguay";
                    break;
                case 6:
                    $imagen = "images/caratula-bancos.PNG";
                    $club = "Club - Encuesta de Organizaciones No Gubernamentales";
                    break;
                case 8:
                    $imagen = "images/caratula-bancos.PNG";
                    $club = "Club de Empresas de Consumo Masivo";
                    break;
                case 14:
                    $imagen = "images/caratula-bancos.PNG";
                    $club = "Club Industrial Especial";
                    break;
                case 16:
                    $imagen = "images/caratula-amx.png";
                    $club = "AMX Especial"; 
                    break;
                case 17:
                    $imagen = "images/caratula-bancos.PNG";
                    $club = "COFCO Especial";
                    break;
                case 18:
                    $imagen = "images/caratula-general.png";
                    $club = "Encuestas de Compensaciones Mercado General";
                    break;
                case 20:
                    $imagen = "images/caratula-naviera.PNG";
                    $club = "Cargill Especial - Navieras";
                    break;
                case 21:
                    $imagen = "images/caratula-agro.PNG";
                    $club = "BASF - Demo";
                    break;
                case 23:
                    $imagen = "images/caratula-autos.PNG";
                    $club = "Club Automotriz, Maquinarias y Utilitarios";
                    break;
                case 24:
                        $imagen = "images/caratula-agro.PNG";
                        $club = "Club ALPACASA";
                        break;
                default:
                    $imagen = "images/caratula-bancos.PNG";
                    $club = "Club de Bancos";
                    break;
            }
        }
        if($getImagen){
            return $imagen;
        }
        return $club;        
    }
}