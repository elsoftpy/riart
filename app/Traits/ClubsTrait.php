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