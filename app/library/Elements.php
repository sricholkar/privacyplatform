<?php

/**
 * Elements
 *
 * Helps to build UI elements for the application
 */
class Elements extends Phalcon\Mvc\User\Component
{

    private $_headerMenu = array(
        'pull-left' => array(
            'index' => array(
                'caption' => 'Home',
                'action' => 'index',
                'class' => 'fa fa-home'
            ),

            'gadgets' => array(
                'caption' => 'Gadgets',
                'action' => 'index',
                'class' => 'fa fa-cogs'
            ),

            'admin' => array(
                'caption' => 'Admin',
                'action' => 'index',
                'class' => 'fa fa-user-circle'
            ),

             'contact' => array(
                'caption' => 'Contact',
                'action' => 'index',
                 'class' => 'fa fa-envelope'
            ),
        ),
        'pull-right' => array(
            'session' => array(
                'caption' => 'LogIn/SignUp',
                'action' => 'index',
            ),
        )
    );

    private $_tabs = array(
        'Edit Gadgets' => array(
            'controller' => 'admin',
            'action' => 'index',
            'any' => false
        ),
        'Admin Gadgets' => array(
            'controller' => 'admingadgets',
            'action' => 'index',
            'any' => false
        ),
        'Feedback' => array(
            'controller' => 'feedback',
            'action' => 'index',
            'any' => false
        ),

        'Your Profile' => array(
            'controller' => 'admin',
            'action' => 'profile',
            'any' => false
        )
    );

    /**
     * Builds header menu with left and right items
     *
     * @return string
     */
    public function getMenu($view, $translate)
    {

        $auth = $this->session->get('auth');
        if ($auth) {
            $this->_headerMenu['pull-right']['session'] = array(
                'caption' => 'LogOut',
                'action' => 'end'
            );
            if ($auth['status']=='user'){
                unset($this->_headerMenu['pull-left']['admin']);
            }
            if ($auth['status']=='admin'){
                unset($this->_headerMenu['pull-left']['contact']);
            }
        } else {
            unset($this->_headerMenu['pull-left']['admin']);
            unset($this->_headerMenu['pull-left']['recentlyused']);
        }


        echo '<div id = "navbar" class="collapse navbar-collapse">';
        $controllerName = $this->view->getControllerName();
        foreach ($this->_headerMenu as $position => $menu) {
            echo '<ul  style="padding:4px 0" class="nav navbar-nav ', $position, '">';
            foreach ($menu as $controller => $option) {
                if ($controllerName == $controller) {
                    echo "<li class='active '>";
                } else {
                    echo '<li>';
                }
                if ($controller == "session") {
                    echo Phalcon\Tag::linkTo(array($controller . '/' . $option['action'],'&thinsp;'.$translate[$option['caption']],
                        'class' => 'fa fa-sign-in'));
                    echo '</li>';
                }
                else {#
                    echo Phalcon\Tag::linkTo(array($controller.'/'.$option['action'],'&thinsp;'. $translate[$option['caption']], 'class' => $option["class"]));
                    echo '</li>';
                }
            }
            echo '</ul>';
        }
        echo '</div>';
    }

    public function getTabs($view, $translate)
    {
        $controllerName = $this->view->getControllerName();
        $actionName = $this->view->getActionName();
        echo '<ul class="admin-nav nav nav-tabs">';
        foreach ($this->_tabs as $caption => $option) {
            if ($option['controller'] == $controllerName && ($option['action'] == $actionName || $option['any'])) {
                echo '<li class="active">';
            } else {
                echo '<li>';
            }
            echo Phalcon\Tag::linkTo($option['controller'].'/'.$option['action'], $translate[$caption]), '<li>';
        }
        echo '</ul>';
    }
}








