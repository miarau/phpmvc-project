<?php
/**
 * Config-file for navigation bar.
 *
 */
return [

    // Use for styling the menu
    'class' => 'navbar',
 
    // Here comes the menu strcture
    'items' => [

        // This is a menu item
        'hem'  => [
            'text'  => 'Hem',   
            'url'   => 'index.php',  
            'title' => 'Hem',
        ],
 
        // This is a menu item
        'redovisning'  => [
            'text'  => 'Redovisning',   
            'url'   => 'redovisning',   
            'title' => 'Redovisning',
			
			 // Here we add the submenu, with some menu items, as part of a existing menu item
            'submenu' => [
                'items' => [

                    // This is a menu item of the submenu
                    'item 1'  => [
                        'text'  => 'kmom01',   
                        'url'   => 'kmom01',  
                        'title' => 'kmom01'
                    ],
					 // This is a menu item of the submenu
                    'item 2'  => [
                        'text'  => 'kmom02',   
                        'url'   => 'kmom02',  
                        'title' => 'kmom02',
                    ],
					
					 // This is a menu item of the submenu
                    'item 3'  => [
                        'text'  => 'kmom03',   
                        'url'   => 'kmom03',  
                        'title' => 'kmom03',
                    ],
					// This is a menu item of the submenu
                    'item 4'  => [
                        'text'  => 'kmom04',   
                        'url'   => 'kmom04',  
                        'title' => 'kmom04'
                    ],
					// This is a menu item of the submenu
                    'item 5'  => [
                        'text'  => 'kmom05',   
                        'url'   => 'kmom05',  
                        'title' => 'kmom05'
                    ],
					'item 6'  => [
                        'text'  => 'kmom06',   
                        'url'   => 'kmom06',  
                        'title' => 'kmom06'
                    ],
                ],
            ],
        ],
 
 		 // This is a menu item
        'Uppgifter'  => [
            'text'  => 'Extra',   
            'url'   => 'uppgifter',   
            'title' => 'Extra',

            // Here we add the submenu, with some menu items, as part of a existing menu item
            'submenu' => [
                'items' => [

                    // This is a menu item of the submenu
                    'item 1'  => [
                        'text'  => 'Kasta tärning',   
                        'url'   => 'dice',  
                        'title' => 'Kasta tärning',
                    ],
					
					 // This is a menu item of the submenu
                    /*'item 2'  => [
                        'text'  => 'CForm',   
                        'url'   => 'cform_test.php',  
                        'title' => 'Test av CForm',	
                    ],*/
                ],
            ],
        ],
		
		// This is a menu item
        'Tema'  => [
            'text'  => 'Tema',   
            'url'   => 'theme.php',  
            'title' => 'tema',        
        ],
		
		// This is a menu item
        'Användare'  => [
            'text'  => 'Användare',   
            'url'   => 'users/list',  
            'title' => 'Användare', 
			'submenu' => [
                'items' => [ 
					
					// This is a menu item of the submenu
                    'LäggTill'  => [
                        'text'  => 'Skapa användare',   
                        'url'   => 'users/add',
                        'title' => 'Lägg till användare.'
                    ],                     

                    // This is a menu item of the submenu
                    'item 4'  => [
                        'text'  => 'Återställ databasen',   
                        'url'   => 'users/setup',  
                        'title' => 'Återskapa Tabell och användare'
                    ], 
                ],
            ], 
        ],
		
		// This is a menu item 
        'Filosofier' => [ 
            'text'  => 'Filosofier',  
            'url'   => 'filosofier',   
            'title' => 'Filosofier' 
        ], 
		
		// This is a menu item 
        'RSS' => [ 
            'text'  => 'RSS',  
            'url'   => 'rss',   
            'title' => 'RSS' 
        ], 
			
        // This is a menu item
        'Källkod' => [
            'text'  => 'Källkod', 
            'url'   => 'source',  
            'title' => 'Källkod'
        ],
		
		
    ],
 
    // Callback tracing the current selected menu item base on scriptname
	// executes in view-class - therefor $this
    'callback' => function($url) {
        if ($url == $this->di->get('request')->getRoute()) {
            return true;
        }
    },

    // Callback to create the urls
    'create_url' => function($url) {
        return $this->di->get('url')->create($url);
    },
];
