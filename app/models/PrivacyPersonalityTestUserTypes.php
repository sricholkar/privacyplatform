<?php

class PrivacyPersonalityTestUserTypes extends Phalcon\Mvc\Model
{
    public function initialize()
    {
        $this->setSource('privacy_personality_user_types');
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
    public $category;
    /**
     *
     * @var integer
     */
    public $main_category;

}
