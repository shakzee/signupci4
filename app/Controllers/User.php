<?php
namespace App\Controllers;
use App\Models\ModUsers;
class User extends BaseController
{
    public function index()
    {
        echo 'user is wroking..';
    }

    public function register()
    {
        helper('form');
        $session = \Config\Services::session();
       $data['message'] = $session->getFlashdata('message');
        echo view('signup',$data);
    }

    public function newuser()
    {
        $myvalues = $this->validate(
            [
                'name'=>'required',
                'email'=>'required',
                'password'=>'required',
            ]
        );
        if (!$myvalues) {
            $this->register();
        }
        else{
            $myrequest = \Config\Services::request();
            $session = \Config\Services::session();

            $users = new ModUsers();
            helper('text');
            $data['u_name'] = $myrequest->getPost('name');
            $data['u_email'] = $myrequest->getPost('email');
            $data['u_password'] = $myrequest->getPost('password');
            $data['u_password'] = hash('md5',$data['u_password']);
            $data['u_link'] = random_string('alnum',20);

            $message = "Please activate the account ".anchor('user/activate/'.$data['u_link'],'Activate Now','');
            $checkAlreadyUser = $users->where('u_email',$data['u_email'])->findAll();
            if (count($checkAlreadyUser) > 0) {
                $session->setFlashdata('message','This email: ' . $data['u_email'] . ' already exist.');
                return redirect()->to(site_url('register'));
                //echo 'the email is already exist.';
            }
            else{
                $myNewUser = $users->insert($data);
                if ($myNewUser) {
                    $email = \Config\Services::email();
                    $email->setFrom('ci4signup@shakzee.com', 'Activate the account');
                    $email->setTo($data['u_email']);
                    $email->setSubject('Activate the account | shakzee.com');
                    $email->setMessage($message);
                    $email->send();
                    $email->printDebugger(['headers']);
                }
                else{
                    $session->setFlashdata('message','We have successfully create the account but we can\'t send you the email right now.');
                    return redirect()->to(site_url('register'));
                }
            }
            //die();


        }
    }

    public function activate($linkHere)
    {
        //echo $linkHere;
        $session = \Config\Services::session();
        $user = new ModUsers();
        $checkUserLink = $user->where('u_link',$linkHere)->findAll();
        if (count($checkUserLink) > 0) {
            $data['u_status'] = 1;
            $data['u_link'] = $checkUserLink[0]['u_link'].'ok';
            $activateUser = $user->update($checkUserLink[0]['u_id'],$data);
            if ($activateUser) {
                $session->setFlashdata('message','We have successfully activate your account.');
                return redirect()->to(site_url('login'));
            }
            else{
                $session->setFlashdata('message','Your link is not available in the system, please check your email address and try again.');
                return redirect()->to(site_url('register'));
            }
        }
        else{
            $session->setFlashdata('message','Something went wrong.');
            return redirect()->to(site_url('register'));
        }
       // var_dump($checkUserLink);
    }

    public function signin()
    {
        $session = \Config\Services::session();
        $data['message'] = $session->getFlashdata('message');
        helper('form');
        echo view('signin',$data);
    }

    public function checkuser()
    {
        $myrequest = \Config\Services::request();
        $session = \Config\Services::session();

        $myvalues = $this->validate(
            [
                'email'=>'required',
                'password'=>'required',
            ]
        );
        if (!$myvalues) {
            $this->signin();
        }
        else{
            $users = new ModUsers();
            helper('text');
            $data['u_email'] = $myrequest->getPost('email');
            $data['u_password'] = $myrequest->getPost('password');
            $data['u_password'] = hash('md5',$data['u_password']);
            $allUsers = $users->where('u_email',$data['u_email'])->findAll();
            if (count($allUsers) > 0) {
                if ($data['u_password'] == $allUsers[0]['u_password']) {
                    $sessionData['u_id'] = $allUsers[0]['u_id'];
                    $sessionData['u_name'] = $allUsers[0]['u_name'];
                    $sessionData['u_email'] = $allUsers[0]['u_email'];
                    $sessionData['u_date'] = $allUsers[0]['u_date'];
                    $sessionData['u_updated'] = $allUsers[0]['u_updated'];
                    $sessionData['u_status'] = $allUsers[0]['u_status'];
                    $session->set($sessionData);
                    if ($session->get('u_id')) {
                       return  redirect()->to(site_url('home'));
                    }
                    else{
                        $session->setFlashdata('message','You can\'t signin, please try again.');
                        return redirect()->to(site_url('login'));
                    }
                }
                else{
                    $session->setFlashdata('message','The password is invalid, please check your password.');
                    return redirect()->to(site_url('login'));
                }
            }else{
                $session->setFlashdata('message','The user is not available in the system.');
                return redirect()->to(site_url('login'));
            }
        }
    }

    public function signout()
    {
        $session = \Config\Services::session();
        $session->destroy();
        return redirect()->to(site_url('login'));
    }
}//class here