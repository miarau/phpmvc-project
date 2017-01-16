<?php

namespace Miax\HTMLForm;

/**
 * Miax base class for wrapping sessions.
 *
 */
class FormSmallController
{
    use \Miax\DI\TInjectionaware;



    /**
     * Index action using external form.
     *
     */
    public function indexAction()
    {
        $this->di->session();

        $form = new \Miax\HTMLForm\CFormExample();
        $form->setDI($this->di);
        $form->check();

        $this->di->theme->setTitle("Testing CForm with Miax");
        $this->di->views->add('default/page', [
            'title' => "Try out a form using CForm",
            'content' => $form->getHTML()
        ]);
    }
}
