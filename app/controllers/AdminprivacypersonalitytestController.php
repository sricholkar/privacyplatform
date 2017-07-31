<?php

use Phalcon\Tag as Tag;
use Phalcon\Mvc\Model\Criteria;

class AdminprivacypersonalitytestController extends ControllerBase
{
    public function initialize()
    {
        $this->view->setTemplateAfter('main');
        Tag::setTitle('Manage Privacy Personality Test');
        parent::initialize();
    }

    public function indexAction()
    {
        $questions = PrivacyPersonalityTestQuestions::find(array("order" =>"id"));
	    $this->view->setVar("questions", $questions);
    }

    
    public function newAction()
    {
    }

    public function createAction()
    {
        if (!$this->request->isPost()) {
            return $this->forward("adminprivacypersonalitytest/index");
        }

        $question = new PrivacyPersonalityTestQuestions();
        $question->id = PrivacyPersonalityTestQuestions::find()->count()+1;
        $question->questions = $this->request->getPost("question", "striptags");
        $question->questionseng = $this->request->getPost("questionEng", "striptags");

        $question->hide = 0;
        if (!$question->save()) {
            foreach ($question->getMessages() as $message) {
                $this->flash->error((string) $message);
            }
            return $this->forward("adminprivacypersonalitytest/new");
        } else {
            $this->flash->success("Question was created successfully");
            return $this->forward("adminprivacypersonalitytest/index");
        }
    }


    public function editAction($id)
    {
        $request = $this->request;
        if (!$request->isPost()) {

            $question = PrivacyPersonalityTestQuestions::findFirst(array('id=:id:', 'bind' => array('id' => $id)));
            //Fetching German Questions from database and passing to view using variable 'question'
	        Tag::setDefault('question', $question->questions);
	        //Fetching English Questions from database and passing to view using variable 'questionEng'
            Tag::setDefault('questionEng', $question->questionseng);

            if (!$question) {
                $this->flash->error("Question was not found");
                return $this->forward("adminprivacypersonalitytest/index");
            }
            $this->view->setVar("id", $question->id);
            
            Tag::displayTo("id", $question->id);
            Tag::displayTo("question", $question->questions);
            Tag::displayTo('questionEng', $question->questionseng);

        }
    }

    public function saveAction()
    {
        if (!$this->request->isPost()) {
            return $this->forward("adminprivacypersonalitytest/index");
        }

        $id = $this->request->getPost("id", "int");
        $question = PrivacyPersonalityTestQuestions::findFirst("id='$id'");
        if ($question == false) {
            $this->flash->error("Question does not exist ".$id);
            return $this->forward("adminprivacypersonalitytest/index");
        }
        $question->id = $this->request->getPost("id", "int");
        $question->questions = $this->request->getPost("question", "striptags");
        $question->questionseng = $this->request->getPost("questionEng", "striptags");



        if (!$question->save()) {
            foreach ($question->getMessages() as $message) {
                $this->flash->error((string) $message);
            }
            return $this->forward("adminprivacypersonalitytest/edit/".$question->id);
        } else {
            $this->flash->success("Question was updated successfully");
            return $this->forward("adminprivacypersonalitytest/index");
        }
    }

    public function deleteAction($id)
    {
        $question = PrivacyPersonalityTestQuestions::findFirst(array('id=:id:', 'bind' => array('id' => $id)));
        if (!$question) {
            $this->flash->error("Question was not found");
            return $this->forward("adminprivacypersonalitytest/index");
        }

        if (!$question->delete()) {
            foreach ($question->getMessages() as $message) {
                $this->flash->error((string) $message);
            }
            return $this->forward("adminprivacypersonalitytest/search");
        } else {
            $this->flash->success("Question was deleted");
            return $this->forward("adminprivacypersonalitytest/index");
        }
   }
    public function hideAction($id,$value)
    {
        $question = PrivacyPersonalityTestQuestions::findFirst(array('id=:id:', 'bind' => array('id' => $id)));
	if (!question){
	    $this->flash->error("Question was not found");
            return $this->forward("adminprivacypersonalitytest/index");
	}
	$question->assign(array(
            'hide' => (int)$value
        ));
        if (! $question->update()) {
            $this->flash->error($question->getMessages());
        } else {
            $this->flash->success("Updated successfully");
        }
        return $this->response->redirect('adminprivacypersonalitytest/index');

    }

}

