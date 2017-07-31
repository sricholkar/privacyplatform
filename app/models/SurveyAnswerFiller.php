<?php

class SurveyAnswerFiller extends Phalcon\Mvc\Model
{

   
   public function initiate($surveyName)
    {
        $this->setSource($surveyName.'Answers');
    }

    public $id;
    public $questions;
    public $username;
    public $answer;
    public $userid;
    public $attempt;


}
