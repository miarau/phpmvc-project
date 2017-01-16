<?php

namespace Miax\MVC;

/**
 * Miax base class for wrapping sessions.
 *
 */
class CApplicationBasic
{
    use \Miax\DI\TInjectable,
        \Miax\MVC\TRedirectHelpers;



    /**
     * Construct.
     *
     * @param array $di dependency injection of service container.
     */
    public function __construct($di)
    {
        $this->di = $di;
    }
}
