<?php

use Phalcon\Tag as Tag;
use Phalcon\Flash as Flash;
use Phalcon\Mvc\Model\Criteria;

class FeedbackController extends ControllerBase
{

	public function initialize()
  	  {
       	 	$this->view->setTemplateAfter('main');
        	Phalcon\Tag::setTitle('Feedbacks');
		$this->loadCustomTrans('feedback');
        	parent::initialize();
   	 }


	public function indexAction()
   	 {
		$feedbacks = Contact::find(array("order" =>"id DESC"));

		$this->view->setVar("feedbacks", $feedbacks);
       
    	}


    	public function deleteAction($id)
    	{

        	$feedbacks = Contact::findFirst(array('id=:id:', 'bind' => array('id' => $id)));
        	if (!$feedbacks) {
            		$this->flash->error("Feedback not found");
            		return $this->forward("feedback/index");
        	}

        	if (!$feedbacks->delete()) {
            		foreach ($feedbacks->getMessages() as $message) {
               	 	$this->flash->error((string) $message);
            	}
            	return $this->forward("feedback/index");
        	} else {
            		$this->flash->success("feedback deleted");
            		return $this->forward("feedback/index");
       	 	}
   	}
}
