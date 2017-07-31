<?php

class SurveyReader extends Phalcon\Mvc\Model
{

   
   public function initiate($surveyName)
    {
        $this->setSource($surveyName);
    }

    public $id;
    public $question;
    public $group;
    public $value;
    public $scale;
    public $important;


}
