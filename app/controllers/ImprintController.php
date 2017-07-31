<?php

class ImprintController extends ControllerBase
{
    public function initialize()
    {
        $this->view->setTemplateAfter('main');
        Phalcon\Tag::setTitle('Privacy');
	$this->loadCustomTrans('imprint');
        parent::initialize();
    }

    public function indexAction()
    {
    }
}

