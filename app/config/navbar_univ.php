<?php
/**
 * Config-file for navigation bar.
 *
 */
return [

    // Use for styling the menu
    'class' => 'navbar',
    'role' => 'custom-dropdown',
    'in_wrapper' => '<input type="checkbox" id="button"><label for="button" onclick></label>',

 
    // Menu strcture
    'items' => [

        'frågor'  => [
            'text'  => 'Frågor',   
            'url'   => 'questions',
            'title' => 'Visa frågor'
        ],
 
        'taggar'  => [
            'text'  => 'Taggar',   
            'url'   => 'questions/tags',
            'title' => 'Visa taggar',
        ],

        'Användare'  => [
            'text'  => 'Användare',   
            'url'   => 'users',  
            'title' => 'Användare',
		],
           
        
	],
 
    // Callback tracing the current selected menu item base on scriptname
	// executes in view-class - therefor $this
   'callback' => function($url) {
        if ($url == $this->di->get('request')->getRoute()) {
            return true;
        }
    },
      /**
     * Callback to check if current page is a decendant of the menuitem, this check applies for those
     * menuitems that has the setting 'mark-if-parent' set to true.
     *
     */
    'is_parent' => function ($parent) {
        $route = $this->di->get('request')->getRoute();
        return !substr_compare($parent, $route, 0, strlen($parent));
    },


    // Callback to create the urls
    /*
    'create_url' => function($parent) {
        return $this->di->get('url')->create($url);
    },
    */
];
