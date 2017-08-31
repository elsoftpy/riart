<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class InvitacionEncuesta extends Mailable
{
    use Queueable, SerializesModels;
    /**
    * link para la encuesta
    *
    * @var link
    */
    public $link; 

    /**
    *
    * nombre del funcionario
    *
    * @var funcionario
    */
    public $nombre;

    /**
    *
    * descripcion de la encuesta
    *
    * @var encuestaCab
    */
    public $encuesta;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($link, $encuesta, $nombre)
    {
        $this->link = $link;
        $this->encuesta = $encuesta;
        $this->nombre = $nombre;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        
        return $this->from('probeman@elsoftpy.com')
                    ->view('emails.encuestaNotificacion');
    }
}
