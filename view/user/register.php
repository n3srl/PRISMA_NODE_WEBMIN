<?php /* @var $User User */ ?> 
<h2>Nuovo utente</h2>
<form method='POST' action='/service/user/saveNew'>    
    <div class='required'><label>Username</label><input type='text' name='username' id='username' required='required'/></div>

    <div class='required'><label>Password</label><input type='password' name='password' id='password' required='required'/></div>
    <div class='required'><label>Conferma password</label><input type='password' name='confirm_password' id='password' required='required'/></div>



    <button>Salva</button>
</form>
