<?php
function Verifier_session(){
    if($_SESSION["connecte"]!=="1"){
        header("location:http://localhost/WEBFINALL/SignIn.html");   
    }
}
?>