<?php
/**
Dont Remove this
the perfect defense for 2010, the Venix/X1478--
*/

$xa = getenv('REMOTE_ADDR');
$badwords = array(";","'","\"","*","union","x:","x:\#","delete ","///","from|xp_|execute|exec|sp_executesql|sp_|select| insert|delete|where|drop table|show tables|#|\*|","DELETE","insert",","|"x'; U\PDATE Character S\ET level=99;-\-","x';U\PDATE Account S\ET ugradeid=255;-\-","x';U\PDATE Account D\ROP ugradeid=255;-\-","x';U\PDATE Account D\ROP ",",W\\HERE 1=1;-\\-","z'; U\PDATE Account S\ET ugradeid=char","update","drop","sele","memb","set" ,"$","res3t","wareh","%","--","666.php","666","/(shutdown|from|select|update|character|clan|set|insert|delete|where|drop table|show tables|#|\*|--|\\\\)/"); 

foreach($_POST as $value) 
foreach($badwords as $word) 
if(substr_count($value, $word) > 0) 
die("<script>alert('Não Use Caracters Invalido!'); location='javascript:history.back()'</script>");
?>