<?php

class PrivacyPersonalityTestQuestions extends Phalcon\Mvc\Model
{

   public function initialize()
    {
        $this->setSource('privacy_personality_test_questions');
    }
    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $questions;

    /**
     *
     * @var string
     */
    public $questionseng;

}

