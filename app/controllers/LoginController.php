<?php

namespace Adso\controllers;

use Adso\Libs\controller;
use Adso\libs\Helper;
use Adso\libs\Session;
use Adso\libs\Email;
use Adso\libs\DateHelper;

class LoginController extends Controller
{
    protected $model;

    function __construct()
    {
        $this->model = $this->model("User");
        

    }

    function index()
    {
        if (isset($_COOKIE['data'])) {
            $data = $_COOKIE['data'];
            $data = Helper::decrypt($data);
            $value = explode('|', $data);
            $user = $value[0];
            $password = $value[1];
            $arra_data = [
                'user' => $user,
                'password' => $password,
                'remember' => 'on'
            ];
        } else {
            $arra_data = [];
        }

        $data = [
            "titulo" => "Login",
            "subtitulo" => "Formulario login",
            'data' => $arra_data
        ];

        $this->view('login', $data, 'auth');
    }

    function validate()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $errorres = array();
            $user       = $_POST['user'] ?? '';
            $password   = $_POST['password'] ?? '';
            $remember   = isset($_POST['remember']) ? 'on' : 'off';

            if ($user == "") {
                $errorres['user_error'] =  "El usuario es requerido";
            }
            if ($password == "") {
                $errorres['password_error'] =  "La contraseña es requerida";
            }
            if (strlen($user) > 50) {
                $errorres['user_error'] =  "El usuario excede el limite de caracteres";
            }
            if (strlen($password) > 50) {
                $errorres['password_error'] =  "La contraseña excede el limite de caracteres";
            }

            $value = $user . '|' . $password;
            $value = Helper::encrypt($value);
            if ($remember == 'on') {
                //Lo recordamos por 1 semana
                $date = time() + (60 * 60 * 24 * 7);
            } else {
                $date = time() - 1;
            }
            setcookie("data", $value, $date, URL);

            if (empty($errorres)) {
                $data = $this->model->validate($user, $password);

                if (empty($data)) {
                    $errorres['password_incorrect'] =  "El usuario o contraseña son incorrectas";
                    $data = [
                        "titulo" => "Login",
                        "subtitulo" => "Formulario login",
                        "errors" => $errorres
                    ];
                    $this->view('login', $data, 'auth');

                } else {
                    //Tomamos el resultado de la consulta
                    $sesion = new Session();
                    $sesion->loginStar($data);

                    header('Location: '.URL.'/admin');

                    
                }
            } else {
                $data = [
                    "titulo" => "Login",
                    "subtitulo" => "Formulario login",
                    "errors" => $errorres
                ];
                $this->view('login', $data, 'auth');
            }
        } else {
            die("!Te pille, ingreso no permitido¡");
        }
    }

    function forgetpassword()
    {
        $data = [
            "titulo" => "Recuperar contraseña",
            "subtitulo" => "Formulario recuperar contraseña"
        ];

        $this->view('forget', $data, 'auth');
    }

    function timestamp($email, $id_user, $userModel)
    {
        $correo = new Email();

        $correo->sendEmail($email, Helper::encrypt($id_user));
        
        // $data = $userModel->chekear($id_user);

        $userModel->createtime($id_user);

        // if ($data == null){
        //     $userModel->createtime($id_user);
        // } else {
        //     $userModel->backnull($id_user);
        // }
    }

    

    function sendEmail()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $errorres = array();
            $email    = $_POST['email'] ?? '';
            if ($email == "") {
                $errorres['email_empty'] =  "El correo es requerido";
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errorres['email_error'] =  "El correo no es valido";
            }
            if (empty($errorres)) {

                $data = $this->model->validateEmail($email);

                if (!empty($data)) {
                    $email = $data['email'];
                    $this->timestamp($email, $data['id_user'], $this->model);
                    // $id = Helper::encrypt($data['id_user']);

                    // $correo = new Email();
                    
                    $data = $this->model->validateEmail($email);
                    //Enviamos al usuario a su vista de correo enviado
                    // header('location:'.URL);
                    // $this->view('email_send', $data, 'auth');
                    header('location:'.URL."/login/emailSend");
                } else {
                    $errorres['email_dontexist'] =  "El correo no existe";
                    $data = [
                        "titulo" => "Recuperar contraseña",
                        "subtitulo" => "Formulario recuperar contraseña",
                        "errors" => $errorres
                    ];
                    $this->view('forget', $data, 'auth');
                }
            } else {
                $data = [
                    "titulo" => "Recuperar contraseña",
                    "subtitulo" => "Formulario recuperar contraseña",
                    "errors" => $errorres
                ];

                $this->view('forget', $data, 'auth');
            }
        } else {
            die("!Te pille, ingreso no permitido¡");
        }
    }

    function emailSend () {
        $data = [
            "titulo" => "Login",
            "subtitulo" => "Formulario login",
        ];

        $this->view('email_send', $data, 'auth');
    }

    function compare($id_user)
    {
        $fecha = $this->model->chekear($id_user);

        $data = DateHelper::timestamp($fecha);

        return $data;
    }

    function updatepassword($id = "")
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $errorres = array();
            $id = $_POST['id'] ?? '';
            $password           = $_POST['password'] ?? '';
            $confirm_password   = $_POST['confirm_password'] ??  '';
            
            //Validamos
            if ($password == "") {
                $errorres['password_error'] =  "La contraseña es requerida";
            }
            if ($confirm_password == "") {
                $errorres['confirm_password'] =  "La confirmación de la contraseña es requerida";
            }
            if ($password  != $confirm_password) {
                $errorres['password_error'] =  "La confirmación no coindice con su contraseña";
            }
            if ($this->compare(Helper::decrypt($id)) > 180){
                $errorres['expire_error'] =  "El link de recuperacion ha expirado";
            }  
            
            // $data = $userModel->chekear($id_user);

            if (!empty($errorres)) {  
                $data = [
                    "titulo" => "Modificar contraseña",
                    "subtitulo" => "Formulario modificar contraseña",
                    "errors" => $errorres,
                    "data" => $id
                ];
                $this->view('update', $data, 'auth');
            } else {
                $id = Helper::decrypt($id);
                //Modificamos la contraseña
                if ($this->model->updatePassword($id, $password)) {

                    $sesion = new Session();
                    $sesion->loginStar($id);
                    //Enviamos al usuario a su vista inicial
                    // header('location:' . URL . '/admin');
                    $data = [
                        "titulo" => "Login",
                        "subtitulo" => "Formulario login",
                    ];
                    // $this->view('admin', $data, 'auth');
                    header("Location:".URL."/admin");
                }
            }
        } else {
            $data = [
                "titulo" => "Modificar contraseña",
                "subtitulo" => "Formulario modificar contraseña",
                "errors" => [],
                "data" => $id
            ];
            $this->view('update', $data, 'auth');
        }
    }
}
