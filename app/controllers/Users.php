<?php

    class Users extends Controller {
        public function __construct(){
            $this->userModel = $this->model('User');
        }

        // handles loading the form on teh register page
        // and submiting the form
        public function register(){
            // check for post
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                // process form

                // sanitize post data
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

                // init data
                $data = [
                    'name' => trim($_POST['name']),
                    'email' => trim($_POST['email']),
                    'password' => trim($_POST['password']),
                    'confirm_password' => trim($_POST['confirm_password']),
                    'name_err' => '',
                    'email_err' => '',
                    'password_err' => '',
                    'confirm_password_err' => ''
                ];

                // validate email
                if(empty($data['email'])){
                    $data['email_err'] = 'please enter email';
                } else {
                    // check email
                    if($this->userModel->findUserByEmail($data['email'])){
                        $data['email_err'] = 'Email is already taken';
                    }
                }

                // validate name
                if(empty($data['name'])){
                    $data['name_err'] = 'please enter name';
                }


                // validate password
                if(empty($data['password'])){
                    $data['password_err'] = 'please enter password';
                } elseif(strlen($data['password']) < 6){
                    $data['password_err'] = 'Password must be at least 6 characters';
                }

                // validate confirm password
                if(empty($data['confirm_password'])){
                    $data['confirm_password_err'] = 'please confirm password';
                } else {
                    if($data['password'] != $data['confirm_password']){
                        $data['confirm_password_err'] = 'Passwords do not match';
                    }
                }

                // make sure errors are empty
                if(empty($data['email_err']) && empty($data['name_err']) && empty($data['password_err']) &&
                empty($data['confirm_password_err'])){
                    // validated

                    // hash password
                    $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

                    // register user
                    if($this->userModel->register($data)){
                        redirect('user/login');
                    } else {
                        die('Something went wrong');
                    }
                } else {
                    // load view with errors
                    $this->view('users/register', $data);
                }
            } else {
                // load form
                // init data
                $data = [
                    'name' => '',
                    'email' => '',
                    'password' => '',
                    'confirm_password' => '',
                    'name_err' => '',
                    'email_err' => '',
                    'password_err' => '',
                    'confirm_password_err' => ''
                ];

                // load view
                $this->view('users/register', $data);
            }


        }

        public function login(){
            // check for post
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                // process form
                // sanitize post data
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

                // init data
                $data = [
                    'email' => trim($_POST['email']),
                    'password' => trim($_POST['password']),
                    'email_err' => '',
                    'password_err' => '',
                ];

                // validate email
                if(empty($data['email'])){
                    $data['email_err'] = 'please enter email';
                }

                // validate email
                if(empty($data['password'])){
                    $data['password_err'] = 'please enter password';
                }

                // make sure errors are empty
                if(empty($data['email_err']) && empty($data['password_err'])){
                    // validated
                    die('SUCCESS');
                } else {
                    // load view with errors
                    $this->view('users/login', $data);
                }

            } else {
                // load form
                // init data
                $data = [
                    'email' => '',
                    'password' => '',
                    'email_err' => '',
                    'password_err' => '',
                ];

                // load view
                $this->view('users/login', $data);
            }
        }
    }
