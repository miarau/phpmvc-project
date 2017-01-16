<?php 
/**
 * This is a Miax frontcontroller.
 *
 */
// Get environment & autoloader and the $app-object.
require __DIR__.'/config_with_app.php';


//Theme configuration. Before theme->render()
$app->theme->configure(MIAX_APP_PATH . 'config/theme_univ.php'); 
//Navbar configuration.
$app->navbar->configure(MIAX_APP_PATH . 'config/navbar_univ.php');

// Set url cleaner url links.
$app->url->setUrlType(\Miax\Url\CUrl::URL_CLEAN);

/****************************************************************************************
 * Add some routes
 *
***************************************************************************************/
//Index
$app->router->add('', function() use ($app){
$app->theme->setTitle("Hem");
    $questions = $app->questions->findQuestions(3);
    $tags = $app->questions->getTags('popular');
    $users = $app->users->findTop();
	
    $app->views->add('univ/index', 
	[
        'questions' => $questions,
        'tags' => $tags,
        'users' => $users
    ]);
	
 });


// Om
$app->router->add('about', function() use ($app) {
    $app->theme->setTitle("Om");
    $content = $app->textFilter->doFilter($app->fileContent->get('about.md'), 'shortcode, markdown');
    $app->views->add('univ/page', [
        'content' => $content
    ]);
});

/* Databas
***************************************************************************/
$app->router->add('setup', function() use ($app) { 
 $setup = new \Miax\Setup\Setup(require MIAX_APP_PATH . 'config/config_mysql.php');
    $setup->addDefault();
    $app->theme->setTitle("Setup");
    $app->views->addString("<h1>Databasen är återställd</h1><p>Du har nu återställt databasen.</p>", 'main');

}); 

/****************************************************************************************
 * End routes
 *
***************************************************************************************/
$app->router->handle();

// Render the response using theme engine.
$app->theme->render();