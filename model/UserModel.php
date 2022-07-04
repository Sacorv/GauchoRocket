<?php

class UserModel {

    private $database;
    private $validator;

    public function __construct($database , $validator) {
        $this->database = $database;
        $this->validator = $validator;
    }

    public function allUsers () {
        return $this->database->query('SELECT * FROM usuario');
    }

    private function validacionesDeDatos ($email, $pass, $repeatPass) {
        //Validaciones
        $data = [];
        $validacionClaveSegura = $this->validator->validarClaveSegura($pass);
        if(count($validacionClaveSegura) > 0){
            $data['errores']=$validacionClaveSegura;
        }
        if(!$this->validator->isValidPass($pass, $repeatPass)){
            $data = ['contraseñasNoCoincidenBool' =>true ];
            $data['errores'] = 'Las contraseñas no coinciden';
            //return "registerView.html";
        }
        if($this->validator->existsUser($email, $this->database)){
            $data = ['emailYaRegistrado' =>true ];
            $data['errores'] = 'El email ya se encuentra registrado';
            //return "registerView.html";
        }
        return $data;
    }


    public function editUser () {

    }

     public function updateCodigoViajero($codigo){
        $id=$_SESSION["id"];
        if(isset($id)){
            return $this->database->updateCodigoViajero($id,$codigo);

        }else{

            return "Error al actualizar el codigo de Viajero";
        }



    }


    public function verificarUser( $id){
            $result = $this->database->verificarCuenta($id);
            return $result;
    }

    public function createUser($firstName, $lastName, $dni, $email, $pass, $repeatPass , $idVerificacion){
        $data = $this->validacionesDeDatos ($email, $pass, $repeatPass);
        if(count($data) == 0){
            $password = md5($pass);
            $resultCreate = $this->database->create($firstName, $lastName, $dni, $email, $password , $idVerificacion);
        }else{
            return $data;
        }
        if ($resultCreate) {
            return $data=[];
        }else{
            $data['errores']= "Ha ocurrido un error inesperado. Intente registrarse nuevamente.";
            return $data;
        }
    }


    public function validarVerificacionDelUsuario($id)
    {
       $result = $this->database->validarVerificacionDelUsuario($id);

        return $result[0]['Verificado'];


    }


}