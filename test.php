<?php



$validated = GUMP::set_field_name("password1", "mot de passe");
$validated = GUMP::set_field_name("password2", "vérification du mot de passe");

$validated = GUMP::is_valid($data, array(
'password1'   => 'required|equalsfield,password2',

));
