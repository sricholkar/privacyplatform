<?php
use Phalcon\Mvc\Model\Query;
use Phalcon\Http\Request;


class SurveyreaderController extends ControllerBase
{
//private $numberPage = 1;
    private $id=1;
    private $id2=16;
    public function initialize()
    {
        $this->view->setTemplateAfter('main');
        Phalcon\Tag::setTitle('Recommendator System');
        parent::initialize();
    }

    public function indexAction()
    {
       //get the name of the survey, find all questions etc 
	//if ($this->request->isPost() == true) {
		//$gadget = $this->request->getPost('gadget');
		$gadget = $_GET["gadget"];
		$this->view->setVar("gadget", $gadget);
	//}
    }


    public function continueAction()
    {	
	//get the name of the survey, find all questions etc 
	if ($this->request->isPost() == true) {
		$gadget = $this->request->getPost('gadget');
		$reader = new SurveyReader();
		$reader->initiate($gadget);
		$rows = $reader::find(array("order" =>"id"));
		$this->view->setVar("rows", $rows);
		$this->view->setVar("gadget", $gadget);
	}
    }

    public function submitAction()
    {
	if ($this->request->isPost() == true) {
		$gadget = $this->request->getPost('gadget');
		$totalquestions = $this->request->getPost('totalquestions');
		$unfilled = 0;
		for ($x = 1; $x <= $totalquestions; $x++) {
			$important=$this->request->getPost("important$x");
			$answer=$this->request->getPost("answer$x");
			$value= $this->request->getPost("value$x");
			//if the important questions are not filled then flash the error message
			if (($important=='*' && $answer == '') && ($important=='*' && $value == '') ){
				$unfilled = $unfilled +1;
			}
		}if ($unfilled > 0){
			$this->flash->error("Please fill the $unfilled important questions");
			//return $this->forward("surveyreader/indext?gadget=$gadget");

		}else{
			//If no user is logged in then save it as username=annon0 and userid=0 else username=username, id= usernameid
        		$auth = $this->session->get('auth');
			if ($auth==false){
	     			$userid= 0;
	     			$username= "annon$userid";
			}
			//Query the active user
			else{
        			$user = Users::findFirst($auth['id']);
				$username= $user->username;
				$userid= $user->id;
			}
			//if user has already filled the questions then overwrite it otherwise write it for new user
			$surveyFiller = new SurveyAnswerFiller();
			$surveyFiller->initiate($gadget);
			$alreadyanswered = SurveyAnswerFiller::find(array("userid IN($userid)"));
			if ($alreadyanswered ==true && $userid==0){

				//query in ascending order to find the last annon user id so that we decrement it. 
				$annonids=$surveyFiller::find(array("order" =>"userid"));

				//if there is no annon user than minimum userid >0 
				//so do nothing i.e username=annon0, userid = 0 , else if already an annon user then assign new id
				if ($annonids[0]->userid > 0){$alreadyanswered=false;}
				else{
					$userid=$annonids[0]->userid-1;
					echo $userid;
					$username="annon$userid";
					$alreadyanswered=false;
				}
			}

			//Update the user's attempt
			if ($alreadyanswered ==true){
				//$alreadyanswered->delete();
				//find the last attempt number of the user
				$alreadyanswered = $alreadyanswered->getLast();
				$attempt = $alreadyanswered->attempt;// gives us the last attempt number
				$attempt = $attempt +1;
			}

			//if request is post then get the questions and the answers and save it in database alongwith username and userid
			for ($x = 1; $x <= $totalquestions; $x++) {
				$surveyFiller = new SurveyAnswerFiller();
				$surveyFiller->initiate($gadget);
    				$question=$this->request->getPost("question$x");
				$answer1=$this->request->getPost("answer$x");
				$answer2=$this->request->getPost("value$x");
				$surveyFiller->username= $username;
				$surveyFiller->userid= $userid;	
				$surveyFiller->questions= $question;
				if ($answer1 != ""){
					$answer = $answer1;
				}else $answer = $answer2;
				$surveyFiller->answers= $answer;
				$surveyFiller->attempt = $attempt;
				$surveyFiller->save();
				echo "<b>Filled by:</b>$username  attempt $attempt&nbsp; <b>Userid:</b>$userid &nbsp; <b>Question:$x)</b> $question &nbsp; <b>Answer:</b>$answer  <br>";
			} 



}		}
	}
   
}
