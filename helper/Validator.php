<?php

class Validator
{
    public function isValidPass($pass, $repeatPass)
    {
        if ($pass == $repeatPass) {
            return true;
        } else {
            return false;
        }
    }

    public function existsUser ($email , $database) {

       if( $database->validarMail($email) == 1){
           return true;
       }else{
           return false;
       }
    }

    public function validarClaveSegura($clave){
        $data=[];
        if(strlen($clave) < 6){
            $data['errores'] ='La clave debe tener al menos 6 caracteres';

        }
        if(strlen($clave) > 16){
            $data['errores'] = "La clave no puede tener más de 16 caracteres";

        }
        if (!preg_match('`[a-z]`',$clave)){
            $data['errores'] =  "La clave debe tener al menos una letra minúscula";

        }
        if (!preg_match('`[A-Z]`',$clave)){
            $data['errores']= "La clave debe tener al menos una letra mayúscula";

        }
        if (!preg_match('`[0-9]`',$clave)){
            $data['errores'] = "La clave debe tener al menos un caracter numérico";

        }
        return $data;
    }


}