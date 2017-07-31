<?php

class ContactController extends ControllerBase
{
    public function initialize()
    {
        $this->view->setTemplateAfter('main');
        Phalcon\Tag::setTitle('Contact us');
	$this->loadCustomTrans('contact');
        parent::initialize();
    }

    public function indexAction()
    {
    }

    public function sendAction()
    {
        if ($this->request->isPost() == true) {

            $name = $this->request->getPost('name', array('striptags', 'string'));
            $email = $this->request->getPost('email', 'email');
            $comments = $this->request->getPost('comments', array('striptags', 'string'));

	    $captcha = $this->request->getPost('g-recaptcha-response');
            $secret = "6Lfx1hUUAAAAAHdUD-i-WrS298mCvr7DrD5r66ed";
            $ip = $_SERVER['REMOTE_ADDR'];
            $rsp = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secret."&response=".$captcha."&remoteip=".$ip);
            $arr = json_decode($rsp, TRUE);

            $contact = new Contact();
            $contact->name = $name;
            $contact->email = $email;
            $contact->comments = $comments;
            $contact->created_at = new Phalcon\Db\RawValue('now()');

	    if($arr['success'] == TRUE) {
            	if ($contact->save() == false) {
                	foreach ($contact->getMessages() as $message) {
                    		$this->flash->error((string) $message);
                	}
            	} else {
                	$this->flash->success('Thanks, We will try to fix the problem as soon as possible');
                	return $this->forward('index/index');
		}
	    } //else {
                //$this->flash->error('Please check the captcha form');
            //}
 	}
        return $this->forward('contact/index');
    }
}
