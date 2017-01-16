<?php
/**
 * Miax base class for wrapping sessions.
 */

namespace Miax\Kernel;

class CMiaxSingleton extends CMiax implements \Miax\ISingleton
{
    use \Miax\TSingleton;



    /**
     * Construct.
     *
     */
    protected function __construct()
    {
        parent::__construct();
    }
}
