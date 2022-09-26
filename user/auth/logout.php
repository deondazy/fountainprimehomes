<?php 
if (!session_id()) {
    session_start();
}

use Okoye\Core\Util;

require __DIR__ . '/../../bootstrap.php';

if ($user->isLogged()) {
    $hash = Util::escape($_COOKIE[$config->cookie->login['name']]);
	
    if ($user->logout($hash)) {
	    Util::flash('success', 'You have logged out', 'alert alert-success');
    	Util::redirect($site->url . '/login/');
    }
}
