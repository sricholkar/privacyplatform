<?php

use Phalcon\Tag as Tag;

class SessionController extends ControllerBase
{
    public function initialize()
    {
        $this->view->setTemplateAfter('main');
        Tag::setTitle('Sign Up/Sign In');
	$this->loadCustomTrans('session');
        parent::initialize();
    }

    public function indexAction()
    {
        if (!$this->request->isPost()) {
            Tag::setDefault('email', '');
            Tag::setDefault('password', '');
        }
    }

    public function registerAction()
    {
        $request = $this->request;
        if ($request->isPost()) {

            $name = $request->getPost('name', array('string', 'striptags'));
            $username = $request->getPost('username', 'alphanum');
            $email = $request->getPost('email', 'email');
	    $birthday = $request->getPost('birthday');
	    $gender = $request->getPost('gender');
            $password = $request->getPost('password');
            $repeatPassword = $request->getPost('repeatPassword');
	
	    //email and username validation
	    $queryEmail = Users::find("email='$email' ")->toArray();
	    if ($queryEmail == false){
		$queryUsername = Users::find("username='$username' ")->toArray();
		if ($queryUsername == false){
            		$user = new Users();
           		$user->username = $username;
            		$user->password = sha1($password);
            		$user->name = $name;
            		$user->email = $email;
            		$user->birthday = $birthday;
            		$user->gender = $gender;
            		$user->created_at = new Phalcon\Db\RawValue('now()');
            		$user->active = 'Y';
	    		$user->status = 'user';
            		if ($user->save() == false) {
                		foreach ($user->getMessages() as $message) {
                    			$this->flash->error((string) $message);
                		}
            		} else {
                		Tag::setDefault('email', '');
                		Tag::setDefault('password', '');
                		$this->flash->success("Thanks for sign-up, please log-in to start using our services" );
                		return $this->forward('session/index');
            		}
		}else {$this->flash->error("The username already exists please try a different one" );}
	    }else{
		$this->flash->error("The email already exists please try a different one" );
	    
           }
	}
    }

    /**
     * Register authenticated user into session data
     *
     * @param Users $user
     */
    private function _registerSession($user)
    {
        $this->session->set('auth', array(
            'id' => $user->id,
            'name' => $user->name,
	    'status'=> $user->status
        ));
    }

    /**
     * This actions receive the input from the login form
     *
     */
    public function startAction()
    {
        if ($this->request->isPost()) {
            $email = $this->request->getPost('email', 'email');
            $password = $this->request->getPost('password');
            $password = sha1($password);
	    $captcha = $this->request->getPost('g-recaptcha-response');

	    $secret = "6Lfx1hUUAAAAAHdUD-i-WrS298mCvr7DrD5r66ed";
            $ip = $_SERVER['REMOTE_ADDR'];
            $rsp = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secret."&response=".$captcha."&remoteip=".$ip);
            $arr = json_decode($rsp, TRUE);
            //if($arr['success'] == TRUE){
            $user = Users::findFirst("email='$email' AND password='$password' AND active='Y'");
	    if ($user != false) {
                $this->_registerSession($user);
              //  $this->flash->success('Welcome ' . $user->name);
                return $this->forward('index/index');	
            }

            $username = $this->request->getPost('email', 'alphanum');
            $user = Users::findFirst("username='$email' AND password='$password' AND active='Y'");
            if ($user != false) {
                $this->_registerSession($user);
            //    $this->flash->success('Welcome ' . $user->name);
                return $this->forward('index/index');
		
            }

            $this->flash->error('Wrong email/password');
            //} else {
              //  $this->flash->error('Please check the captcha form');
            //}
        }

        return $this->forward('session/index');
    }

    /**
     * Finishes the active session redirecting to the index
     *
     * @return unknown
     */
    public function endAction()
    {
        $this->session->remove('auth');
       //$this->flash->success('Goodbye!');
        return $this->forward('index/index');
    }
}
