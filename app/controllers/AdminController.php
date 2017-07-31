<?php

use Phalcon\Tag as Tag;
use Phalcon\Flash as Flash;
use Phalcon\Session as Session;

class AdminController extends ControllerBase
{
    public function initialize()
    {
        $this->view->setTemplateAfter('main');
        Tag::setTitle('Manage your Gadgets');
	$this->loadCustomTrans('admin');
        parent::initialize();
    }

    public function indexAction()
    {
	$lang = $this->session->get('language');
	$gadgets = Gadgets::find(array("order" =>"id"));
	$this->view->setVar("gadgets", $gadgets);
	$this->view->setVar("language", (string)$lang);
    }

     // Edit the active user profile
  
    public function profileAction()
    {
        //Get session info
        $auth = $this->session->get('auth');

        //Query the active user
        $user = Users::findFirst($auth['id']);
        if ($user == false) {
            $this->_forward('index/index');
        }

        $request = $this->request;

        if (!$request->isPost()) {
            Tag::setDefault('name', $user->name);
            Tag::setDefault('email', $user->email);
        } else {
	    //1)get all the input fields
	    //2) if password fields are empty then do normal saving stuff
	    //3)if password fields are not empty then match it with the database, then check the new password and retyped password fields. the save everything
            $name = $request->getPost('name', 'string');
            $email = $request->getPost('email', 'email');
	    $currentPassword= $request->getPost('password');
            $newPassword= $request->getPost('newpassword');
	    $repeatPassword= $request->getPost('repeatpassword');
	    if ($currentPassword=='' && $newPassword=='' && $repeatPassword==''){
		$name = strip_tags($name);
            	$user->name = $name;
            	$user->email = $email;
		if ($user->save() == false) {
                	foreach ($user->getMessages() as $message) {
                    	$this->flash->error((string) $message);
	                }
		} else {
                $this->flash->success('Your profile information was updated successfully');
                }		
	    }else {
		if (sha1($currentPassword) != $user-> password){
			 $this->flash->error("The current password is wrong" );
		}else{ 
			if ($newPassword != $repeatPassword){
				$this->flash->error('The new password does not match with retyped password');
			}elseif ($newPassword=='' && $repeatPassword==''){
				$this->flash->error('New password should not be empty');
			}elseif (strlen($newPassword) < 8 ){
				$this->flash->error('New password length should be not less than 8 characters');
			}else{
				$name = strip_tags($name);
            			$user->name = $name;
            			$user->email = $email;
				$user->password= sha1($newPassword);
				if ($user->save() == false) {
                			foreach ($user->getMessages() as $message) {
                    				$this->flash->error((string) $message);
                			}
            			} else {
                			$this->flash->success('Your profile information/password was updated successfully');
           			 }
                        }
		}
	    
	    }

        }
    }
}
