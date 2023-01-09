<?php namespace App\Controllers;

class Home extends BaseController
{
	public function index()
	{
        $session = \Config\Services::session();
        $checkUser = $session->get('u_id');
		//return view('welcome_message');
        if ($checkUser) {
            echo 'welcome: ' . $session->get('u_name');
        }
        else{
            echo 'redirect here...';
        }
	}

    public function method()
    {
        echo 'another method..';
    }

    public function setSession()
    {
        $mySession = session();
        $myArray = [
                'name'=>[10,20,30],
                'email'=>'info@shakzee.com',
                'city'=>'karachi',
        ];

        //$mySession->set('name','shakzee');
        $mySession->set($myArray);

    }

    public function getSession()
    {

        $mySession = session();
        if ($mySession->has('name')) {
            $myvar = $mySession->get('name');
        }
        else{
            echo 'error here';
        }
        //echo $mySession->get('name');
    }

    public function destroySession()
    {
        $mySession = session();
        $mySession->destroy();
    }
	//--------------------------------------------------------------------

}
