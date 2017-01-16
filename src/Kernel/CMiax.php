<?php

namespace Miax\Kernel;

/**
 * Miax base class for an application.
 *
 */
class CMiax
{
    use \Miax\DI\TInjectable;



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
