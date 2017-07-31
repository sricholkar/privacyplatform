<?php
use \Phalcon\Db\Column as Column;
class SurveygeneratorController extends ControllerBase
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
		//gets the survey name
        	$surveyName = $this->request->getPost('surveyname', array('striptags', 'string'));
		//format the name in a special order inorder to be saved in a database
		$surveyName = strtolower(str_replace(' ', '', $surveyName));
		
		//get the total number of question and pass it to the view
		$totalQuestions= $this->request->getPost('totalquestions', array('striptags', 'int'));
		$this->view->setVar("totalquestions", $totalQuestions);
		$this->view->setVar("surveyName", $surveyName);
		$information= $this->request->getPost('information', array('striptags', 'string'));
		
		//validatetion i.e check if all required infos are given
		if ($surveyName=='' || $totalQuestions=="" || $information ==""){
			
			if ($surveyName==""){
				$this->flash->error('Please specify the survey name');
				return $this->forward('surveygenerator/index');
			}
			if ($totalQuestions==0){
				$this->flash->error('Please specify the number of questions');
				return $this->forward('surveygenerator/index');
			}
			if ($information ==""){
				$this->flash->error('Please provide the information about the survey');
				return $this->forward('surveygenerator/index');
			}
						
		}else{
		//add the survey in the gadget table in the database
			$newGadget = new Gadgets();
			$newGadget->name = $surveyName;
			$newGadget->generatedby = 'SurveyGenerator';
			$newGadget->information = $information;
			$newGadget->save();
		//create 2 tables in the database for storing the survey questions and the answers 
			return $this->generateTable($surveyName);
		}
    	}
    }
    private function generateTable($tableName)
    {
	$config = [
    		"host"     => "localhost",
    		"username" => "root",
   		"password" => "1234",
    		"dbname"   => "privacyplatform",
	];
	$connection = new \Phalcon\Db\Adapter\Pdo\Mysql($config);
	$connection->createTable(
    		"$tableName",
    		null,
    		[
       			"columns" => [
            			new Column(
                			"id",
                			[
                    				"type"          => Column::TYPE_INTEGER,
                    				"size"          => 10,
                    				"notNull"       => true,
                    				"autoIncrement" => true,
                    				"primary"       => true,
                			]
            			),
				new Column(
                			"question",
                			[
                    				"type"    => Column::TYPE_VARCHAR,
                    				"size"    => 300,
                    				"notNull" => false,
                			]
            			),
            			new Column(
                			"group",
                			[
                    				"type"    => Column::TYPE_VARCHAR,
                    				"size"    => 20,
                    				"notNull" => false,
                			]
            			),
            			new Column(
                			"value",
                			[
                    				"type"    => Column::TYPE_VARCHAR,
                    				"size"    => 5,
                    				"notNull" => false,
                			]
            			),
				new Column(
                			"scale",
                			[
                    				"type"    => Column::TYPE_INTEGER,
                    				"size"    => 11,
                    				"notNull" => false,
                			]
            			),
				new Column(
                			"important",
                			[
                    				"type"    => Column::TYPE_VARCHAR,
                    				"size"    => 5,
                    				"notNull" => false,
                			]
            			),
        			]
    		]
		);
	
	$connection->createTable(
    		$tableName.'Answers',
    		null,
    		[
       			"columns" => [
            			new Column(
                			"id",
                			[
                    				"type"          => Column::TYPE_INTEGER,
                    				"size"          => 11,
                    				"notNull"       => true,
                    				"autoIncrement" => true,
                    				"primary"       => true,
                			]
            			),
				new Column(
                			"questions",
                			[
                    				"type"    => Column::TYPE_VARCHAR,
                    				"size"    => 300,
                    				"notNull" => false,
                			]
            			),
            			new Column(
                			"username",
                			[
                    				"type"    => Column::TYPE_VARCHAR,
                    				"size"    => 30,
                    				"notNull" => false,
                			]
            			),
            			new Column(
                			"userid",
                			[
                    				"type"    => Column::TYPE_INTEGER,
                    				"size"    => 11,
                    				"notNull" => false,
                			]
            			),
				new Column(
                			"attempt",
                			[
                    				"type"    => Column::TYPE_INTEGER,
                    				"size"    => 11,
                    				"notNull" => false,
                			]
            			),
				new Column(
                			"answers",
                			[
                    				"type"    => Column::TYPE_VARCHAR,
                    				"size"    => 10,
                    				"notNull" => false,
                			]
            			),
        			]
    		]
		);
	
    }
    public function generateAction()
    {
	if ($this->request->isPost() == true) {
		$surveyName = $this->request->getPost('surveyname');
		$totalQuestions = $this->request->getPost('totalquestions');

		for ($x = 0; $x < $totalQuestions; $x++) {
			//initilize the table in the modal in which the data will be saved
			$surveyGenerator = new SurveyGenarator();
			$surveyGenerator->initiate($surveyName);
			$question = $this->request->getPost("question$x");
			$important =$this->request->getPost("importance$x");
			$scale  =$this->request->getPost("scale$x");
			$group  =$this->request->getPost("group$x");
			$value  =$this->request->getPost("value$x");
			$surveyGenerator->question = $question;
			$surveyGenerator->important = $important;
			$surveyGenerator->scale  = $scale;
			$surveyGenerator->group  = $group;
			$surveyGenerator->value  = $value;
			$surveyGenerator->save();
		}
		if ($surveyGenerator->save() === false) {
    		echo "Umh, We can't store questions right now: \n";

    		$messages = $surveyGenerator->getMessages();

    		foreach ($messages as $message) {
        		echo $message, "\n";
   			 }
		} else {
    			echo "Great, a new survey was saved successfully!";
		}
	}
    }
 
}
