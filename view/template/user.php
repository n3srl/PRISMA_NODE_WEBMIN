<?php

$user = UserFactory::getCurrent();
if (!isset($user)) {
    echo "<a href='/user/login'>Login</a>";
    echo "<a href='/user/register'>Nuovo Utente</a>";
} else {
    echo "<a href='/user/edit/$user->id'>$user->username </a>";
    echo "<a href='/user/logout'>Esci</a>";
}
?>