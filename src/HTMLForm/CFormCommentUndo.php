<?php

namespace Miax\HTMLForm;

/**
 * Form to add comment
 *
 */
class CFormCommentUndo extends \Mos\HTMLForm\CForm
{
    use \Miax\DI\TInjectionaware,
        \Miax\MVC\TRedirectHelpers;

    private $redirect;

    /**
     * Constructor
     *
     */
    public function __construct($redirect)
    {
        parent::__construct([], [
        
            'regret' => [
                'type'  => 'hidden',
                'value' => 'regret',
            ],
 
            
            'undo' => [
                'type'      => 'submit',
                'callback'  => [$this, 'callbackUndo'],
                'value'     => 'Ångra',
            ],

            
        ]);
        
        $this->redirect = $redirect;
    }



    /**
     * Customise the check() method.
     *
     * @param callable $callIfSuccess handler to call if function returns true.
     * @param callable $callIfFail    handler to call if function returns true.
     */
    public function check($callIfSuccess = null, $callIfFail = null)
    {
        return parent::check([$this, 'callbackSuccess'], [$this, 'callbackFail']);
    }



    /**
     * Callback for submit-button.
     *
     */
    public function callbackUndo()
    {
    if ($this->Value('regret')=='regret') {
        return true;
        }
    }




    /**
     * Callback What to do if the form was submitted?
     *
     */
    public function callbackSuccess()
    {
         $this->redirectTo($this->redirect);
    }


    /**
     * Callback What to do when form could not be processed?
     *
     */
    public function callbackFail()
    {
        $this->AddOutput("<p><i>Form was submitted and the Check() method returned false.</i></p>");
        //$this->redirectTo('comments/edit');
    }
}