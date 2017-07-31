<?php

class SurveyGenarator extends Phalcon\Mvc\Model
{

   
   public function initiate($surveyName)
    {
        $this->setSource($surveyName);
    }
    /**
     *
     * @var integer
     */
    public $id;
    public $question;
    public $group;
    public $value;
    public $scale;
    public $important;


}
