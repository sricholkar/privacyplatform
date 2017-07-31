<?php

class IndexController extends ControllerBase
{
    public function initialize()
    {
        $this->view->setTemplateAfter('main');
        Phalcon\Tag::setTitle('Welcome');
	$this->loadCustomTrans('index');
        parent::initialize();
    }

    public function indexAction()
    {
        $language = $this->session->get('language');        
    }

    public function languageAction($language)
    {
        //Change the language, reload translations if needed
        if ($language == 'en' || $language == 'de') {
            $this->session->set('language', $language);
            $this->loadMainTrans();
            $this->loadCustomTrans('index');
        }
        //Go to the last place
        $referer = $this->request->getHTTPReferer();
        if (strpos($referer, $this->request->getHttpHost()."/")!==false) {
            return $this->response->setHeader("Location", $referer);
        } else {
            return $this->dispatcher->forward(array('controller' => 'index', 'action' => 'index'));
        }

    }
}
