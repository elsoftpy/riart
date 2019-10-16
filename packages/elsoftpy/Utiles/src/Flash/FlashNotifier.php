<?php namespace elsoftpy\Utiles\Flash;

class FlashNotifier
{
    /**
     * The session writer.
     *
     * @var SessionStore
     */
    private $session;
    /**
     * Create a new flash notifier instance.
     *
     * @param SessionStore $session
     */
    function __construct(SessionStore $session)
    {
        $this->session = $session;
    }
    /**
     * Flash an information message.
     *
     * @param string $message
     * @return $this
     */
    public function info($message)
    {
        $this->message($message, 'info');
        return $this;
    }
    /**
     * Flash a success message.
     *
     * @param  string $message
     * @return $this
     */
    public function success($message)
    {
        $this->message($message, 'success');
        return $this;
    }
    /**
     * Flash an error message.
     *
     * @param  string $message
     * @return $this
     */
    public function error($message)
    {
        $this->message($message, 'danger');
        return $this;
    }
    /**
     * Flash a warning message.
     *
     * @param  string $message
     * @return $this
     */
    public function warning($message)
    {
        $this->message($message, 'warning');
        return $this;
    }
    /**
     * Flash an overlay modal.
     *
     * @param  string $message
     * @param  string $title
     * @param  string $level
     * @return $this
     */
    public function overlay($message, $level = 'info', $number = '000')
    {
        if($level=='info'){
            $title = 'Sabías que...';
            $class = 'modal-info';
            $this->messageModal($message, $number, $title, $class);    
        }elseif ($level=='warning') {
            $title = 'Cuiado';
            $class = 'modal-warning';
            $this->messageModal($message, $number, $title, $class);    
            
        }elseif ($level=='success') {
            $title = 'Buenísimo!';
            $class = 'modal-success';
            $this->messageModal($message, $number, $title, $class);    
        }elseif ($level=='error') {
            $title = 'Lo sentimos';
            $class = 'modal-error';
            $this->messageModal($message, $number, $title, $class);    
        }

        $this->session->flash('flash_notification.overlay', true);
        

        return $this;
    }
    /**
     * Flash a general message.
     *
     * @param  string $message
     * @param  string $level
     * @return $this
     */
    public function message($message, $level = 'info')
    {
        $this->session->flash('flash_notification.message', $message);
        $this->session->flash('flash_notification.level', $level);
        return $this;
    }

    /**
     * Flash a general message.
     *
     * @param  string $message
     * @param  string $level
     * @param  string $class
     * @return $this
     */
    public function messageModal($message, $number, $title, $class )
    {
        $this->session->flash('flash_notification.message', $message);
        $this->session->flash('flash_notification.number', $number);
        $this->session->flash('flash_notification.title', $title);
        $this->session->flash('flash_modal_class', $class);
        return $this;
    }

    /**
     * Add an "important" flash to the session.
     *
     * @return $this
     */
    public function important()
    {
        $this->session->flash('flash_notification.important', true);
        return $this;
    }

    /**
     * Clears Flash a general message.
     *
     * @param  string $message
     * @param  string $level
     * @param  string $class
     * @return $this
     */
    public function clearFlash()
    {
        $this->session->forget('flash_notification.message');
        $this->session->forget('flash_notification.number');
        $this->session->forget('flash_notification.title');
        $this->session->forget('flash_modal_class');
        return $this;
    }


     /**
     * Sets notification messages.
     *
     * @return $this
     */
    public function elsoftMessage($code, $modal = false)
    {
        
        switch ($code) {
            // info
            case 0:
                if($modal){
                    $this->overlay('Mensaje de Prueba', 'info', 'S&B - 0');    
                }else{
                    $this->info('Mensaje de Prueba - Info');    
                }                
                break;
            case 10:
                if($modal){
                    $this->overlay('El Reporte no está disponible por el momento </br> Favor comuníquese con la consultora para mayor información', 'info', 'S&B - 10');
                }else{
                    $this->info('El Reporte no está disponible por el momento </br> Favor comuníquese con al consultora para mayor información', 'info');
                }
                break;
            // warning
            case 1000:
                if($modal){
                    $this->overlay('Mensaje de Prueba', 'warning', 'S&B - 1000');    
                }else{
                    $this->warning('Mensaje de Prueba - Atención');    
                }
                break;
            case 1001:
                if($modal){
                    $this->overlay('No tenés permiso de acceso al Formulario', 'warning', 'S&B - 1001');    
                }else{
                    $this->warning('No tenés permiso de acceso al Formulario');    
                }
                break;
            // success                
            case 2000:
                if($modal){
                    $this->overlay('Mensaje de Prueba', 'success', 'S&B - 2000');    
                }else{
                    $this->success('Mensaje de Prueba - Exito');    
                }

                break;
            case 2010:
                if($modal){
                    $this->overlay('El registro fue guardado con éxito', 'success', 'S&B - 2010');    
                }else{
                    $this->success('El registro fue guardado con éxito');    
                }
                break;           
            case 2011:
                if($modal){
                    $this->overlay('El registro fue eliminado con éxito', 'success', 'S&B - 2011');    
                }else{
                    $this->success('El registro fue eliminado con éxito');    
                }
                break;                  
            case 2012:
                if($modal){
                    $this->overlay('El registro de la Gerencia fue eliminado', 'success', 'S&B - 2012');    
                }else{
                    $this->success('El registro de la Gerencia fue eliminado');    
                }
                break;                  
            case 2013:
                if($modal){
                    $this->overlay('El registro del Area fue eliminado', 'success', 'S&B - 2013');    
                }else{
                    $this->success('El registro del Area fue eliminado');    
                }
                break;                                  
            case 2014:
                if($modal){
                    $this->overlay('El registro de la Sub Area fue eliminado', 'success', 'S&B - 2014');    
                }else{
                    $this->success('El registro de la Sub Area fue eliminado');    
                }
                break;  
            case 2015:
                if($modal){
                    $this->overlay('El registro del Cargo fue eliminado', 'success', 'S&B - 2015');    
                }else{
                    $this->success('El registro del Cargo fue eliminado');    
                }
                break;                                                                    
            case 2016:
                if($modal){
                    $this->overlay('El registro del Funcionario fue eliminado', 'success', 'S&B - 2016');    
                }else{
                    $this->success('El registro del Funcionario fue eliminado');    
                }
                break;                                                                    

            //Errors    
            case 3000:
                if($modal){
                    $this->overlay('Mensaje de Prueba', 'error', 'S&B - 3000');    
                }else{
                    $this->error('Mensaje de Prueba - Error');    
                }
                break;
            case 3010:
                if($modal){
                    $this->overlay('La encuesta que querés eliminar ya fue utilizada, por eso no puede ser borrada', 'error', 'S&B - 3010');    
                }else{
                    $this->error('Mensaje de Prueba - Error');    
                }
                break;
            case 3011:
                if($modal){
                    $this->overlay('El registro de la Persona que querés eliminar está relacionado con otro registro, por eso no puede ser borrado', 'error', 'S&B - 3011');    
                }else{
                    $this->error('El registro de la Persona que querés eliminar está relacionado con otro registro, por eso no puede ser borrado - Error');    
                }
                break;            
            case 3012:
                if($modal){
                    $this->overlay('Hubo un error inesperado al intentar eliminar el registro que seleccionaste.', 'error', 'S&B - 3012');    
                }else{
                    $this->error('Hubo un error inesperado al intentar eliminar el registro que seleccionaste. - Error');    
                }
                break;                            
            case 3013:
                if($modal){
                    $this->overlay('Hubo un error inesperado al intentar insertar o actualizar el registro que seleccionaste', 'error', 'S&B - 3013');    
                }else{
                    $this->error('Hubo un error inesperado al intentar insertar o actualizar el registro que seleccionaste - Error');    
                }
                break;                                            
            case 3014:
                if($modal){
                    $this->overlay('La Sub Area que querés eliminar está relacionada con otro registro, por eso no puede ser borrada', 'error', 'S&B - 3014');    
                }else{
                    $this->error('La Sub Area que querés eliminar está relacionada con otro registro, por eso no puede ser borrada - Error');    
                }
                break;                                            
            case 3015:
                if($modal){
                    $this->overlay('El Cargo que querés eliminar está relacionado con otro registro, por eso no puede ser borrado', 'error', 'S&B - 3015');    
                }else{
                    $this->error('El Cargo que querés eliminar está relacionado con otro registro, por eso no puede ser borrado - Error');    
                }
                break;                                            

            case 3016:
                if($modal){
                    $this->overlay('El registro de Funcionario que querés eliminar está relacionado con otro registro, por eso no puede ser borrado', 'error', 'S&B - 3016');    
                }else{
                    $this->error('El registro de Funcionario que querés eliminar está relacionado con otro registro, por eso no puede ser borrado - Error');    
                }
                break;      
            case 4001:
                if($modal){
                    $this->overlay('Error de integridad en la Base de Datos. </br> Ha introducido un valor nulo en un campo que no admite dicho valor. </br> La información guardada podría no mostrarse correctamente.', 'warning', 'S&B - 4000');
                }else{
                    $this->warning('Error en la base de datos (Valor nulo no válido) -Atención');
                }                                        
                break;
            case 4002:
                if($modal){
                    $this->overlay('Error de integridad en la Base de Datos. </br> Ha intentado introducir una clave única ya existente.', 'error', 'S&B - 4002');
                }else{
                    $this->error('Error en la base de datos (Valor duplicado) -Error');
                }                                        
                break;
            case 4003:
                if($modal){
                    $this->overlay('Error de integridad en la Base de Datos. </br> No se puede borrar o actualizar el registro porque posee otros registros relacionados a él', 'error', 'S&B - 4003');
                }else{
                    $this->error('Error en la base de datos (Registros relacionados al que desea borrar o actualizar) -Error');
                }                                        
                break;

                


        }

        return $this;
    }
    
   

}