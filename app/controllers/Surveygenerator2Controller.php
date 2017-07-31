<?php
// created by: Behttani
// date : 30.01.2017
// This controller will be used as an admin gadget to generate advance surveys, which will classify questions into sectons

use \Phalcon\Db\Column as Column;
class Surveygenerator2Controller extends ControllerBase
{
    public function initialize()
  	  {
       	 	$this->view->setTemplateAfter('main');
        	Phalcon\Tag::setTitle('Generate survey');
        	parent::initialize();
   	 }
    public function indexAction()
    	{
    	}
    public function nextAction()
    	{
		if ($this->request->isPost() == true) {
			//get the survey name and information
        		$surveyName = $this->request->getPost('surveyname', array('striptags', 'string'));
			//format the name in a special order inorder to be saved in a database
			$surveyName2 = strtolower(str_replace(' ', '', $surveyName));
		
			//get the total number of question and pass it to the view
			$this->view->setVar("surveyName", $surveyName);
			$information= $this->request->getPost('information', array('striptags', 'string'));
		
			//validatetion i.e check if all required infos are given
			if ($surveyName==''){
				$this->flash->error('Please specify the survey name');
				return $this->forward('surveygenerator2/index');
			}
		}
						
   	}
}
