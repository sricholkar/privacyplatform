<?php

class AboutController extends ControllerBase
{
    public function initialize()
    {
        $this->view->setTemplateAfter('main');
        Phalcon\Tag::setTitle('About us');
	$this->loadCustomTrans('about');
        parent::initialize();
    }

    public function indexAction()
    {
    }
}
