<?php
use Phalcon\Mvc\Model\Query;
class GadgetsController extends ControllerBase
{
    public function initialize()
    {
        $this->view->setTemplateAfter('main');
        Phalcon\Tag::setTitle('Gadgets');
	$this->loadCustomTrans('gadgets');
        parent::initialize();
    }

    public function indexAction()
    {
       
	// Instantiate the Query
	$query = new Query( "SELECT * FROM Gadgets",$this->getDI());

	// Execute the query returning a result if any
	$gadgets = $query->execute();
	
	//Find the language of the session
	$lang = $this->session->get('language');

	$this->view->setVar("gadgets", $gadgets);
	$this->view->setVar("language", (string)$lang);
     }
}
