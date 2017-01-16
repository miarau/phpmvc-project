<?php

namespace Miax\DI;

/**
 * Miax base class implementing Dependency Injection / Service Locator 
 * of the services used by the framework, using lazy loading.
 *
 */
class CDIFactoryDefault extends CDI
{
   /**
     * Construct.
     *
     */
    public function __construct()
    {
        parent::__construct();

        require MIAX_APP_PATH . 'config/error_reporting.php';

        $this->setShared('response', '\Miax\Response\CResponseBasic');
        $this->setShared('validate', '\Miax\Validate\CValidate');
        $this->setShared('flash', '\Miax\Flash\CFlashBasic');
        
        $this->set('route', '\Miax\Route\CRouteBasic');
        $this->set('view', '\Miax\View\CViewBasic');

        $this->set('ErrorController', function () {
            $controller = new \Miax\MVC\ErrorController();
            $controller->setDI($this);
            return $controller;
        });

        $this->setShared('log', function () {
            $log = new \Miax\Log\CLogger();
            $log->setContext('development');
            return $log;
        });

        $this->setShared('request', function () {
            $request = new \Miax\Request\CRequestBasic();
            $request->init();
            return $request;
        });
		//creates all links
        $this->setShared('url', function () {
            $url = new \Miax\Url\CUrl();
            $url->setSiteUrl($this->request->getSiteUrl());
            $url->setBaseUrl($this->request->getBaseUrl());
            $url->setScriptName($this->request->getScriptName());
            $url->setUrlType($url::URL_APPEND);
            return $url;
        });

        $this->setShared('views', function () {
            $views = new \Miax\View\CViewContainerBasic();
            $views->setBasePath(MIAX_APP_PATH . 'view');
            $views->setFileSuffix('.tpl.php');
            $views->setDI($this);
            return $views;
        });

        $this->setShared('router', function () {
            
            $router = new \Miax\Route\CRouterBasic();
            $router->setDI($this);

            $router->addInternal('403', function () {
                $this->dispatcher->forward([
                    'controller' => 'error',
                    'action' => 'statusCode',
                    'params' => [
                        'code' => 403,
                        'message' => "CRouter says: This is a forbidden route.",
                    ],
                ]);
            })->setName('403');
            
            $router->addInternal('404', function () {
                $this->dispatcher->forward([
                    'controller' => 'error',
                    'action' => 'statusCode',
                    'params' => [
                        'code' => 404,
                        'message' => "CRouter says: This route is not found.",
                    ],
                ]);
            })->setName('404');
            
            $router->addInternal('500', function () {
                $this->dispatcher->forward([
                    'controller' => 'error',
                    'action' => 'statusCode',
                    'params' => [
                        'code' => 500,
                        'message' => "CRouter says: There was an internal server or processing error.",
                    ],
                ]);
            })->setName('500');
            
            return $router;
        });

        $this->setShared('dispatcher', function () {
            $dispatcher = new \Miax\MVC\CDispatcherBasic();
            $dispatcher->setDI($this);
            return $dispatcher;
        });

        $this->setShared('session', function () {
            $session = new \Miax\Session\CSession();
            $session->configure(MIAX_APP_PATH . 'config/session.php');
            $session->name();
            $session->start();
            return $session;
        });
		//creates theme
        $this->setShared('theme', function () {
            $themeEngine = new \Miax\ThemeEngine\CThemeBasic();
            $themeEngine->configure(MIAX_APP_PATH . 'config/theme.php');
            $themeEngine->setDI($this);
            return $themeEngine;
        });
		//creates navbar
        $this->setShared('navbar', function () {
            $navbar = new \Miax\Navigation\CNavbar();
            $navbar->configure(MIAX_APP_PATH . 'config/navbar.php');
            $navbar->setDI($this);
            return $navbar;
        });
		//reads files from disk
        $this->set('fileContent', function () {
            $fc = new \Miax\Content\CFileContent();
            $fc->setBasePath(MIAX_APP_PATH . 'content/');
            return $fc;
        });

        $this->setShared('textFilter', function () {
            $filter = new \Miax\Content\CTextFilter();
            $filter->configure(MIAX_APP_PATH . 'config/text_filter.php');
            return $filter;
        });
    }
}
