<?php
use Phalcon\Mvc\Model\Query;
use Phalcon\Http\Request;


class PrivacypersonalitytestController extends ControllerBase
{
//private $numberPage = 1;
    private $id=1;
    private $id2=16;
    public function initialize()
    {
        $this->view->setTemplateAfter('main');
        Phalcon\Tag::setTitle('Privacy Personality Test');
	$this->loadCustomTrans('privacypersonalitytest');
        parent::initialize();
    }

    public function indexAction()
    {
       
    }

    public function continueAction($lang)
    {	
	$lang = $this->session->get('language');
	$questions = PrivacyPersonalityTestQuestions::find("hide = 0");
	$this->view->setVar("questions", $questions);
	$auth = $this->session->get('auth');
	$this->view->setVar("auth", $auth);
	$this->view->setVar("language", (string)$lang);
    }
    

    public function meanCalc($answer, $scale){
	
	return $scale/$answer;
    }

    public function submitAction()
    {
	$auth = $this->session->get('auth');
        $this->view->setVar("auth", $auth);

	$captcha = $this->request->getPost('g-recaptcha-response');
        $secret = "6Lfx1hUUAAAAAHdUD-i-WrS298mCvr7DrD5r66ed";
        $ip = $_SERVER['REMOTE_ADDR'];
        $rsp = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secret."&response=".$captcha."&remoteip=".$ip);
        $arr = json_decode($rsp, TRUE);
        $random = $this->request->get('random');

	//If no user is logged in then save it as username=annon0 ans userid=0 else username=username, id= usernameid

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
	$alreadyanswered = PrivacyPersonalityTestAnswers::find(array("userid IN($userid)"));
	if ($alreadyanswered ==true && $userid==0){

	//query in ascending order to find the last annon user id so that we decrement it. 
		$annonids= PrivacyPersonalityTestAnswers::find(array("order" =>"userid"));

	//if there is no annon user than minimum userid >0 so do nothing i.e username=annon0, userid = 0 , else if already an annon user then assign new id
		if ($annonids[0]->userid > 0){$alreadyanswered=false;}
		else{
			$userid=$annonids[0]->userid-1;
			$username="annon$userid";
			$attempt = 1;
			$alreadyanswered=false;
		}
	}

	//update the new attempt of the user
	if ($alreadyanswered ==true){
		//$alreadyanswered->delete();
		//find the last attempt number of the user
		$alreadyanswered = $alreadyanswered->getLast();
		$attempt = $alreadyanswered->attempt;// gives us the last attempt number
		$attempt = $attempt +1;
	}
	 $this->view->setVar("userid", $userid);
        $this->view->setVar("attempt", $attempt);
        $this->view->setVar("username", $username);

	$new = array(); //this array will save the ($answer*100/10) values
	$questions = array();
        $answers =array();

	if($arr['success'] == TRUE || $auth == true || $random >= 0.2)
	{
              $questionCount = PrivacyPersonalityTestQuestions::count();
	      $this->view->setVar("questionCount", $questionCount);
	//if request is post then get the questions and the answers and save it in database alongwith username and userid
	for ($x = 1; $x <= $questionCount; $x++) {
		$recomAnswers = new PrivacyPersonalityTestAnswers();
    		$question=$this->request->getPost("question$x");
		$answer=$this->request->getPost("answer$x");
		$recomAnswers->username=$username;
		$recomAnswers->userid=$userid;
		$recomAnswers->questions=$question;
		$recomAnswers->answers=$answer;
		$recomAnswers->attempt=$attempt;
		array_push($answers, $answer);
		array_push($new , ($answer*100)/10);
		array_push($questions, $question);
		$recomAnswers->save();
		echo "<b>Filled by:</b>$username &nbsp; <b>Userid:</b>$userid &nbsp; <b>Question:$x)</b> $question &nbsp; <b>Answer:</b>$answer ,";

		}

	$this->view->setVar("answers", $answers );
            $this->view->setVar("questions", $questions);
            $this->view->setVar("qAnswer", $new);
            print_r($new);

	//array of usertypes 
	$userTypes = ["Laid-back users", "Casual observers", "Information flow inspectors", "Privacy risk managers", "Protectors of personal identifiable information", "Personal identifiable information-spurred investigators", "Information collection and flow controllers", "Controllers of personal identifiable information", "Fair user controllers", "Privacy practice scrutinizers"];

	//Three major groups of users
	$majorGroups = array(
		"Laid-back users" => "Guarded Information Seekers", 
		"Casual observers"=>"Guarded Information Seekers", 
		"Information flow inspectors"=>"Guarded Information Seekers", 
		"Privacy risk managers"=> "Pragmatic Information Seekers", 
		"Protectors of personal identifiable information"=>"Pragmatic Information Seekers", 
		"Personal identifiable information-spurred investigators"=>"Pragmatic Information Seekers", 
		"Information collection and flow controllers"=>"Pragmatic Information Seekers", 
		"Controllers of personal identifiable information"=>"Committed Information Seekers", 
		"Fair user controllers"=>"Committed Information Seekers", 
		"Privacy practice scrutinizers"=>"Committed Information Seekers");

	//10 arrays of cluster variables. Each cluster represents a user type

    $c1 = array(1.3,        1.3,    26,     1,      4,      11.5,   13.3,   13.5,   28.3,   30);
        $c2 = array(45.5,       34.5,   39.6,   33,     41,     32.5,   46.2,   49.4,   47.7,   49.3);
        $c3 = array(90.1,       70.4,   63.1,   40.1,   37.6,   41.6,   59.1,   83.7,   10.4,   24.1);
        $c4 = array(82.5,       47.1,   44.1,   22.8,   57.6,   51.2,   59.4,   65.4,   71.2,   69.4);
        $c5 = array(93.2,       61.6,   70.4,   64.1,   91.7,   56.5,   90.4,   79.9,   46.6,   48.7);
        $c6 = array(75,         56,     68.5,   50.7,   76.5,   67,     75.4,   79.4,   66.3,   61.8);
        $c7 = array(100,        96.6,   82.6,   93.2,   74.6,   78.2,   97,     93.8,   37.6,   8.6);
        $c8 = array(90.2,       65.3,   82.3,   79.3,   97,     84.8,   90.6,   84.6,   81.3,   73.7);
        $c9 = array(92.4,       76.6,   77.2,   74.5,   79.6,   73.4,   91,     88.6,   87.2,   78.7);
        $c10 = array(98.3,      94.8,   97,     92.7,   92.9,   88.9,   98.8,   98.6,   92.2,   93.1);

	//find the distances between the new array and clusters. 
	$clusters = array($c1, $c2, $c3, $c4, $c5, $c6, $c7, $c8,$c9, $c10);
	$distances=[];
	
	foreach($clusters as $cluster){
		$i=0;
		$sumSquare=0;
		foreach( $cluster as $c){
			$sumSquare= $sumSquare + pow($new[$i]-$c , 2);
			$i++;
		}

		//find the squareroot of the sumSqaure which is the distance 
		$distance= sqrt($sumSquare);
		
		//append the distance in the distances array
		array_push($distances, $distance);
		
	}
	//the one with the least distance is the group the user belongs to
	$minValue= min($distances);

	//find the index of the minimum value in the array
	$indexOfMin= array_search($minValue, $distances);

	//find the cluster name that corresponds to that index
	$userType = $userTypes[$indexOfMin];
	$mainType = $majorGroups[$userType];
	$this->view->setVar("userType", $userType);
        $this->view->setVar("majorGroup", $mainType);

	//Save userid, username, usertype, mainType in another table i.e PrivacyPersonalityTestUserTypes
	//query if a record is available in the PrivacyPersonalityTestUserTypes
       $userIdCheck = PrivacyPersonalityTestUserTypes::findFirst(array("userid IN($userid)"));
	if ($userIdCheck==true){
		//save the id of the deleted user for update
		$saveId= $userIdCheck->id;
		$userIdCheck->delete();
	}
	$recomUserType = new PrivacyPersonalityTestUserTypes();
	if($userIdCheck == true){
		$recomUserType->id = $saveId;
	}
	$recomUserType->username=$username;
	$recomUserType->userid=$userid;
	$recomUserType->main_category= $mainType;
	$recomUserType->category=$userType;
	$recomUserType->save();


	//show total percentage of all Main user types
	// Instantiate the Query to find percentage of main categories
	$query = new Query( "SELECT main_category, COUNT(main_category)/(select count(*) from PrivacyPersonalityTestUserTypes)*100 as Percentage from PrivacyPersonalityTestUserTypes  Group BY main_category",$this->getDI());

	// Execute the query returning a result if any
	$userTypePercentage = $query->execute();
	//pass the variable to the corresponding view
	$this->view->setVar("userMainPercentage", $userTypePercentage);
	
	//show total percentage of all user types in sub category
	// Instantiate the Query
	$query = new Query( "SELECT category, COUNT(category)/(select count(*) from PrivacyPersonalityTestUserTypes WHERE main_category='$mainType')*100 as Percentage from PrivacyPersonalityTestUserTypes WHERE main_category='$mainType' Group BY category",$this->getDI());

	// Execute the query returning a result if any
	$userTypePercentage = $query->execute();
	//pass the variable to the corresponding view
        $this->view->setVar("userSubPercentage", $userTypePercentage);



	
	 } else {
            $this->flash->error('Please check the captcha form');
            return $this->forward('privacypersonalitytest/continue');
        }
    }
}

