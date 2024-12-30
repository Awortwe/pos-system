<?php  

require 'config/function.php';

if($_SESSION['loggedIn'])
{
    logoutSession();
    redirect('login.php', 'Logged out successfully');
}

?>