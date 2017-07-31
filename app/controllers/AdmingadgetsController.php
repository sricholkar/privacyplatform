<?php

use Phalcon\Tag as Tag;
use Phalcon\Flash as Flash;
use Phalcon\Session as Session;

class AdmingadgetsController extends ControllerBase
{
    public function initialize()
    {
        $this->view->setTemplateAfter('main');
        Tag::setTitle('Admin Gadgets');
	$this->loadCustomTrans('admingadgets');
        parent::initialize();
    }

    public function indexAction()
    {
	
    }
}
