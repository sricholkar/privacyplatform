<?php

class RecentlyusedController extends ControllerBase
{
    public function initialize()
    {
        $this->view->setTemplateAfter('main');
        Phalcon\Tag::setTitle('Recently used');
        parent::initialize();
    }

    public function indexAction()
    {
        
    }
}
