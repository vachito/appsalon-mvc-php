<?php
    namespace Controllers;

    use Classes\Email;
    use Model\Usuario;
    use MVC\Router;
    
    class LoginController{
        public static function login(Router $router){
            $alertas=[];

            if($_SERVER['REQUEST_METHOD']=== 'POST'){
                $auth=new Usuario($_POST);
                $alertas=$auth->validarLogin();
                
                if (empty($alertas)){
                    //comprobar que exista el usuario
                    $usuario=Usuario::where('email',$auth->email);
                    
                    if($usuario){
                        //verificar password
                        if($usuario->comprobarPasswordAndVerificado($auth->password)){
                            //autenticar al usuario
                            session_start();
                            $_SESSION['id']=$usuario->id;
                            $_SESSION['nombre']=$usuario->nombre . " " . $usuario->apellido ;
                            $_SESSION['email']=$usuario->email;
                            $_SESSION['login']=true;

                            //redireccionamiento
                            if($usuario->admin==="1"){
                                $_SESSION['admin']=$usuario->admin ?? null;
                                header('Location: /admin');
                            }else{
                                header('Location: /cita');
                            }
                        }
                    }else{
                        Usuario::setAlerta('error','Usuario no encontrado');
                    }
                }
            }

            $alertas=Usuario::getAlertas();
            $router->render('auth/login',[
                'alertas'=>$alertas
            ]);
        }

        public static function logout(){
            session_start();
            $_SESSION=[];
            header('Location: /');
        }

        public static function olvide(Router $router){
            $alertas=[];

            if($_SERVER['REQUEST_METHOD']==='POST'){
                $auth=new Usuario($_POST);
                $alertas=$auth->validarEmail();

                if(empty($alertas)){
                    $usuario=Usuario::where('email',$auth->email);
                    
                    if($usuario && $usuario->confirmado==='1'){
                        //Generar un token
                        $usuario->crearToken();

                        //almacena el token en la base de datos
                        $usuario->guardar();
                        
                        //enviar el email
                        $email=new Email($usuario->email,$usuario->nombre,$usuario->token);
                        $email->enviarInstrucciones();
                        Usuario::setAlerta('exito','Revisa tu email');
                    }else{
                        Usuario::setAlerta('error','El usuario no existe o no esta confirmado');
                        
                    }                    
                }
            }

            $alertas=Usuario::getAlertas();
            $router->render('auth/olvide-password',[
                'alertas'=>$alertas
            ]);
        }

        public static function recuperar(Router $router){
            $alertas=[];
            $error=false;

            $token=s($_GET['token']);
            $usuario=Usuario::where('token',$token);

            if(empty($usuario)){
                Usuario::setAlerta('error','Token no valido');
                $error=true;
            }

            if($_SERVER['REQUEST_METHOD']==='POST'){
                //Leer el nuevo password y guardarlo
                $password=new Usuario($_POST);
                $alertas=$password->validarPassword();
                
                if(empty($alertas)){
                    $usuario->password=null;
                    $usuario->password=$password->password;
                    $usuario->hashPassword();
                    $usuario->token=null;
                    $resultado=$usuario->guardar();

                    if($resultado){
                        header('Location: /');
                    }
                }
            }

            $alertas=Usuario::getAlertas();
            $router->render('auth/recuperar-password',[
                'alertas'=>$alertas,
                'error'=>$error
            ]);
        }

        public static function crear(Router $router){
            $usuario = new Usuario;

            //alertas vacias
            $alertas=[];

            if ($_SERVER['REQUEST_METHOD']==='POST'){
                $usuario->sincronizar($_POST);
                $alertas=$usuario->validarNuevaCuenta();

                //revisar que alertas este vacio
                if(empty($alertas)){
                    //verificar que el usuario no este registrado
                    $resultado=$usuario->existeUsuario();
                    
                    if($resultado->num_rows){
                        $alertas=Usuario::getAlertas();
                    }else{
                        //Hashear el password
                        $usuario->hashPassword();

                        //GENERAR UN TOKEN UNICO
                        $usuario->crearToken();

                        //Enviar el Email
                        $email=new Email($usuario->email,$usuario->nombre,$usuario->token);
                        
                        $email->enviarConfirmacion();

                        //Crear el usuario
                        $resultado=$usuario->guardar();
                        
                        if($resultado){
                            header('Location: /mensaje');
                        }
                    }
                }
            }

            $router->render('auth/crear-cuenta',[
                'usuario'=>$usuario,
                'alertas'=>$alertas
            ]);
        }

        public static function mensaje(Router $router){
            $router->render('auth/mensaje');
        }

        public static function confirmar(Router $router){
            $alertas=[];

            $token=s($_GET['token']);
            
            $usuario=Usuario::where('token',$token);
            
            if(empty($usuario)){
                //mostrar un mensaje de error
                Usuario::setAlerta('error','Token No valido');
            }else{
                //Modificar el usuario a confirmado
                
                $usuario->confirmado ="1";
                $usuario->token =null;

                $usuario->guardar();
                Usuario::setAlerta('exito','Cuenta Comprobada Correctamente');
            }

            $alertas=Usuario::getAlertas();
            $router->render('auth/confirmar-cuenta',[
                'alertas'=>$alertas
            ]);
        }
    }