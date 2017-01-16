<?php

namespace Miax\Users;

/**
 * A controller for users and admin related events.
 *
 */
class UsersController implements \Miax\DI\IInjectionAware
{
    use \Miax\DI\TInjectable;
    

/**
 * Initialize the controller.
 *
 * @return void
 */
public function initialize()
{
    $this->users = new \Miax\Users\Users();
    $this->users->setDI($this->di);
}


/**
     * List all users.
     *
     * @return void
     */
    public function indexAction()
    {
        $users = $this->users->findAll();

        $this->theme->setTitle("alla användare");
        $this->views->add('users/list', [
            'users' => $users,
            'title' => "alla användare"
        ], 'main');
    }

/**
 * List user with id.
 *
 * @param int $id of user to display
 *
 * @return void
 */
public function viewAction($acronym = null)
{ 
   $this->initialize(); 
      
        $user = $this->users->findByAcronym($acronym); 
      
        $data = $this->questions->findByAuthor($acronym);

        $this->theme->setTitle("användare: ".$acronym);
        $this->views->add('users/view', [
            'user' => $user,
            'data' => $data 
        ], 'main'); 
}


/**
 * Add new user.
 *
 * @param string $acronym of user to add.
 *
 * @return void
 */
public function addAction(){

   $this->theme->setTitle('lägg till användare'); 

        // Get the form
        $form = self::userForm();

        // Check the status of the form
        $status = $form->check();
         
        if ($status === true) {
            $this->users->save([
                'acronym' => $form->value('acronym'),
                'email' => $form->value('email'),
                'name' => $form->value('name'),
                'password' => password_hash( $form->value('password'), PASSWORD_DEFAULT)
            ]);

            $this->response->redirect($this->url->create('users/login'));
        }

        $this->theme->setTitle('skapa konto');
        $this->views->addString("<h1>skapa konto</h1>" . $form->getHTML(), 'main');

}

/** 
 * Edit user. 
 * 
 * @return void 
 */ 
public function editAction() 
{ 
    // Get the user
        $user = $this->users->findByAcronym($this->session->get('acronym'));

        // Get the form
        $form = self::userForm($user);

        // Check the status of the form
        $status = $form->check();
         
        if ($status === true) {
            $this->users->save([
                'acronym' => $form->value('acronym'),
                'email' => $form->value('email'),
                'name' => $form->value('name')
            ]);

            if ($form->value('password') <> '') {
                $this->users->save([
                    'password' => password_hash( $form->value('password'), PASSWORD_DEFAULT)
                ]);
            }

            $this->session->set('acronym', $form->value('acronym'));
            $this->response->redirect($this->url->create('users/view/'.$form->value('acronym')));
        }

        $this->theme->setTitle('ändra din profil');
        $this->views->addString("<h1>redigera din profil</h1>" . $form->getHTML(), 'main');
} 


/**
 * Delete user.
 *
 * @param integer $id of user to delete.
 *
 * @return void
 */
public function deleteAction($id = null)
{
    if (!isset($id)) {
        die("Missing id");
    }
 
    $res = $this->users->delete($id);
 
    $url = $this->url->create('users/list');
    $this->response->redirect($url);
}


/**
 * Delete (soft) user.
 *
 * @param integer $id of user to delete.
 *
 * @return void
 */
public function softDeleteAction($id = null)
{
    if (!isset($id)) {
        die("Missing id");
    }
 
   // $now = date(DATE_RFC2822);
    $now = date("Y-m-d H:i:s"); 
      
        $user = $this->users->find($id); 
      
        $user->deleted = $now; 
        $user->save(); 
      
        $url = $this->url->create('users/list'); 
        $this->response->redirect($url); 
}


/** 
 * Undo soft delete 
 * 
 * @param integer $id of user to undo soft remove for. 
 * 
 * @return void 
 */ 
public function undoDeleteAction($id = null) 
{ 
    if (!isset($id)) { 
        die("Missing id"); 
    } 
  
    $user = $this->users->find($id); 
  
    $user->deleted = NULL; 
    $user->save(); 
  
    $url = $this->url->create('users/list'); 
    $this->response->redirect($url); 
}

/** 
 * Inactivate user. 
 * 
 * @param integer $id of user to inactivate. 
 * 
 * @return void 
 */ 
public function inactivateAction($id = null) 
{ 
    if (!isset($id)) { 
        die("Missing id"); 
    } 
  
    $user = $this->users->find($id); 
  
    $user->active = NULL; 
    $user->save(); 
  
    $url = $this->url->create('users/list'); 
    $this->response->redirect($url); 
} 

/**
 * Reactivate user.
 *
 * @param integer $id of user to reactivate.
 *
 * @return void
 */
public function activateAction($id = null)
{
    if (!isset($id)) {
        die("Missing id");
    }
 
   // $now = date(DATE_RFC2822);
    $now = date("Y-m-d H:i:s"); 
      
    $user = $this->users->find($id); 
  
    $user->active = $now; 
    $user->save(); 
  
    $url = $this->url->create('users/list'); 
    $this->response->redirect($url); 
}


/**
 * List all active and not deleted users.
 *
 * @return void
 */
public function activeAction()
{
    $all = $this->users->query()
        ->where('active IS NOT NULL')
        ->andWhere('deleted is NULL')
        ->execute();
 
    $this->theme->setTitle("aktiva användare");
    $this->views->add('users/list-all', [
        'users' => $all,
        'title' => "aktiva användare",
    ]);
}

/**
     * Log in user.
     *
     * @return void
     */
    public function loginAction()
    {
        // Find out if referral page was restricted
        if (empty($this->session->has('denied')))
            $msg = "";
        else
            $msg = "<p>Sidan du försökte nå kräver att du är inloggad. Logga in med dina uppgifter nedan, eller <a href='{$this->url->create('users/add')}'>skapa ett konto</a>.</p>";

        // Get the form
        $form = self::loginForm($this->session->get('denied'));
        $this->session->set('denied', null);

        // Check the status of the form
        $status = $form->check();
         
        if ($status === true) {
            // Get userinfo
            $user = $this->users->findByAcronym($form->value('acronym'));

            // Check if user exist and password is ok
            if ($user && password_verify($form->value('password'), $user->getProperties()['password'])) {
                $this->session->set('acronym', $form->value('acronym'));
                $this->response->redirect($this->url->create($form->value('referral')));
            }
            
            else {
                $form->output = "<span style='color:red;'>Användarnamnet eller lösenordet stämmer inte. Försök igen eller skapa ett konto.</span>";
            }
        }

        $this->theme->setTitle('logga in');
        $this->views->addString("<h1>logga in</h1>" . $msg . $form->getHTML(), 'main');
    }

    /**
     * Log out user.
     *
     * @return void
     */
    public function logoutAction()
    {
        // Unset session
        $this->session->set('acronym', null);
        $this->theme->setTitle('Utloggad');
        $this->views->addString("<h1>Du är utloggad</h1><p><a href='{$this->url->create('')}'>Tillbaka till startsidan.</a></p>", 'main');
    }

    /**
     * Userform.
     *
     * @param object $user user to edit.
     *
     * @return object the form.
     */
    private function userForm($user = null) {
        $info = null;
        $legend = 'Dina uppgifter';
        $pwMsg = '';
        $pwLogin = ['not_empty'];
        if (!is_null($user)) {
            $info = $user->getProperties();
            $legend = "<h2>". $info['acronym'] . "</h2>";
            $pwMsg = 'Fylls endast i om du vill ändra lösenordet.';
            $pwLogin = [];
        }
        $form = new \Mos\HTMLForm\CForm(['legend'=>$legend], [
                'id' => [
                    'type'       => 'hidden',
                    'value'      => $info['id']
                ],
                'name' => [
                    'type'       => 'text',
                    'label'      => 'Namn',
                    'value'      => $info['name'],
                    'required'    => true, 
                    'validation' => ['not_empty']
                ],
                'acronym' => [
                    'type'       => 'text',
                    'label'      => 'Användarnamn',
                    'value'      => $info['acronym'],
                    'required'    => true, 
                    'validation' => ['not_empty']
                ],
                'email' => [
                    'type'       => 'text',
                    'label'      => 'E-post',
                    'value'      => $info['email'],
                    'required'    => true, 
                    'validation' => ['not_empty', 'email_adress']
                ],
                'password' => [
                    'type'       => 'password',
                    'label'      => 'Lösenord',
                    'description'=>  $pwMsg,
                    'required'    => true, 
                    'validation' => $pwLogin
                ],
                'password_confirm' => [
                    'type'       => 'password',
                    'label'      => 'Lösenordet igen',
                    'required'    => true, 
                    'validation' => ['match' => 'password']
                ],
                'submit' => [
                    'type'       => 'submit',
                    'value'      => 'Skicka',
                    'callback'   => function ($form) {
                        $form->saveInSession = false;
                        return true;
                    }
                ],
                'reset' => [
                    'type'       => 'reset',
                    'value'      => 'Ångra',
                    'callback'   => function ($form) {
                        $form->saveInSession = false;
                        return true;
                    }
                ],
                'delete' => [
                    'type'       => 'submit',
                    'value'      => 'Radera konto',
                    'url'        => $this->url->create('users/delete/'. $info['id']),
                    //'onclick'    => 'this.form.action = $this->url->create("users/delete/". $info["id"])',
                    'callback'   => function($url) {
                        if ($url == $this->di->get('request')->getRoute()) {
                            return true;
                        }
                    }
                ]
            ]);
        return $form;
    }

    /**
     * Login form.
     *
     * @param object $user user to edit.
     *
     * @return object the form.
     */
    private function loginForm($ref=null) {
        $this->theme->addStylesheet('css/form.css');
        $form = new \Mos\HTMLForm\CForm(['legend'=>'Inloggning'], [
                'referral' => [
                    'type'       => 'hidden',
                    'value'      => $ref
                ],
                'acronym' => [
                    'type'       => 'text',
                    'label'      => 'Användarnamn',
                    'validation' => ['not_empty']
                ],
                'password' => [
                    'type'       => 'password',
                    'label'      => 'Lösenord',
                    'validation' => ['not_empty']
                ],
                'submit' => [
                    'type'       => 'submit',
                    'value'      => 'Logga in',
                    'callback'   => function ($form) {
                        $form->saveInSession = false;
                        return true;
                    }
                ]
            ]);
        return $form;
    }


}