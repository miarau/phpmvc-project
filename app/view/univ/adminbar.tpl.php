<?php

if ( $this->session->has('acronym') ) {
    $userHandle = "<a href='{$this->url->create('users/edit')}'>{$this->session->get('acronym')}</a> | <a href='{$this->url->create('users/logout')}'>logga ut</a>";
}
else {
    $userHandle = "<a href='{$this->url->create('users/add')}'>skapa konto</a> | <a href='{$this->url->create('users/login')}'>logga in</a>";
}

?>

<div><a href="<?=$this->url->create('about')?>">om</a> | <?=$userHandle?></div>