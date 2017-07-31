<?php

class PrivacyPersonalityTestAnswers extends Phalcon\Mvc\Model
{
    public function initialize()
    {
        $this->setSource('privacy_personality_test_answers');
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
    public $username;
    /**
     *
     * @var integer
     */
    public $userid;

    /**
     *
     * @var string
     */
    public $questions;
    /**
     *
     * @var integer
     */
    public $answers;


}
