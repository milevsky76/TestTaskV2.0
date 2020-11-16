<?php

    if (!empty($_COOKIE['sid'])) {
        session_id($_COOKIE['sid']);
    }
    
    session_start();
    
    require_once 'User.php';

?>

<noscript>
   Вы не можете зарегистрироваться без включения javascript.
</noscript>

<div class="container" id="registration" style="display: none">

    <?php
    
    if(User::CheckAuthorization()){
        require_once "views/authorized.php";
    }else{ 
        require_once "views/register.php";
    }
    
    ?>

</div>

<script type="text/javascript">
  document.getElementById('registration').style.display = 'block';
</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="js/ajax-form.js"></script>