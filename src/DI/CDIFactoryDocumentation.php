<?php

namespace Miax\DI;

/**
 * Extended factory for Miax documentation.
 *
 */
class CDIFactoryDocumentation extends CDIFactoryDefault
{
   /**
     * Construct.
     *
     */
    public function __construct()
    {
        parent::__construct();

        $this->set('documentation', function() {
            $fc = new \Miax\Content\CFileContent();
            $fc->setBasePath(MIAX_INSTALL_PATH . 'docs/');
            return $fc;
        });
    }
}
