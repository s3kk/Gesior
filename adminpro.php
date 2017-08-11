<?PHP
//README: if nick contain "'" use "\'". For example: nick = The'Knight so use in the form The\'Knight ,,,-WORKS!
if($group_id_of_acc_logged >= $config['site']['access_admin_panel']) {
$main_content .= 'Welcome to advanced admin panel! Mainly you can edit players.
Coded by <a href="http://otland.net/members/turs0n/">turson</a>';
//admin panel menu (player+account)
$main_content .= '<br /><br /><div style="width:100%;border:1px solid #c6c6c6;padding:3px 0 3px 0">
 <b>EDIT PLAYER -></b>
<a href="?subtopic=admin&action=namelock">Name</a> | 
<a href="?subtopic=admin&action=exp">Experience</a> |
<a href="?subtopic=admin&action=level">Level</a> |
<a href="?subtopic=admin&action=others">Others</a> |
<a href="?subtopic=admin&action=groupedit">Group ID</a> | 
<a href="?subtopic=admin&action=delete">Delete</a> <br>
<b>EDIT ACCOUNT -></b>
<a href="?subtopic=admin&action=acc">Account Number</a> | 
<a href="?subtopic=admin&action=pass">Password</a> | 
<a href="?subtopic=admin&action=mail">E-mail</a> | 
<a href="?subtopic=admin&action=access">Access</a>  <br><center><font color="red"><b>PLAYER MUST BE  OFFLINE!</b></font></center>
</div><br>';
/////////////////////////////////CHARACTER////////////////////////////////////
//namelock
if (isset($_POST['ok1'])){
$SQL->query("UPDATE `players` SET `name` = ".$SQL->quote(  $_POST['newname'] )." WHERE name = ".$SQL->quote( $_POST['name0']  ).";");
$main_content .= "Player name has been changed.";} 
//exp
if (isset($_POST['ok2'])){
$SQL->query("UPDATE `players` SET experience = experience +  ".$SQL->quote( $_POST['exp'] )." WHERE name = ".$SQL->quote(  $_POST['name1'] ).";");
$main_content .= "Experience has been added.";} 
//level
if (isset($_POST['ok3'])){
$SQL->query("UPDATE `players` SET level = ".$SQL->quote(  $_POST['level'] )." WHERE name = ".$SQL->quote( $_POST['name2']  ).";");
$main_content .= "Level has been set.";} 
//others
if (isset($_POST['ok4'])){
$SQL->query("UPDATE `players` SET health=".$SQL->quote(  $_POST['health'] ).",healthmax=".$SQL->quote( $_POST['health']  ).",mana=".$SQL->quote( $_POST['mana'] ).",manamax=".$SQL->quote(  $_POST['mana'] ).",cap=".$SQL->quote( $_POST['cap']  ).",maglevel=".$SQL->quote( $_POST['maglevel'] )." WHERE name =  ".$SQL->quote( $_POST['name3'] ).";");
$main_content .= "Informations has been changed.";} 
//delete
if (isset($_POST['ok5'])){
$SQL->query("DELETE from `players` WHERE name = ".$SQL->quote( $_POST['name4'] ).";");
$main_content .= "Character has beed deleted.";} 
//pos
if (isset($_POST['ok10'])){
$SQL->query("UPDATE `players` SET group_id = ".$SQL->quote(  $_POST['newpos'] )." WHERE name = ".$SQL->quote( $_POST['name9']  ).";");
$main_content .= "Player group has been changed.";} 
/////////////////////////////////////////ACCOUNT///////////////////////////////////////
//acc
if (isset($_POST['ok6'])){
$SQL->query("UPDATE `accounts` INNER JOIN `players` ON  `accounts`.`id` = `players`.`account_id` SET  accounts.name=".$SQL->quote( $_POST['newacc'] )." WHERE players.name =  ".$SQL->quote( $_POST['name5'] ).";");
$main_content .= "Account number has beed changed.";} 
//password
if (isset($_POST['ok7'])){
$SQL->query("UPDATE `accounts` INNER JOIN `players` ON  `accounts`.`id` = `players`.`account_id` SET  accounts.password=".$SQL->quote( $_POST['newpass'] )." WHERE  players.name = ".$SQL->quote( $_POST['name6'] ).";");
$main_content .= "Password has beed changed.";} 
//mail
if (isset($_POST['ok8'])){
$SQL->query("UPDATE `accounts` INNER JOIN `players` ON  `accounts`.`id` = `players`.`account_id` SET  accounts.email=".$SQL->quote( $_POST['newmail'] )." WHERE  players.name = ".$SQL->quote( $_POST['name7'] ).";");
$main_content .= "E-Mail adress has beed changed.";} 
//mail
if (isset($_POST['ok9'])){
$SQL->query("UPDATE `accounts` INNER JOIN `players` ON  `accounts`.`id` = `players`.`account_id` SET  accounts.page_access=".$SQL->quote( $_POST['newaccess'] )." WHERE  players.name = ".$SQL->quote( $_POST['name8'] ).";");
$main_content .= "Page access has beed changed.";} 
////////////CHARACTER FORM///////////
//namelock form
if($_GET["action"]=="namelock"){ 
$main_content .= '<form action="?subtopic=admin" method="post">
New Player Name <input type="text" name="newname"><br>
Old Player Name <input type="text"  name="name0"><br><input type="submit" value="OK"  name="ok1"></form>';}
//exp form
if($_GET["action"]=="exp"){ 
$main_content .= '<form action="?subtopic=admin" method="post">
Add experience <input type="text" name="exp"> (only numbers)<br>
Player Name <input type="text" name="name1"><br><input type="submit" value="OK" name="ok2"></form>';}
//level form
if($_GET["action"]=="level"){ 
$main_content .= '<form action="?subtopic=admin" method="post">
Set player level <input type="text" name="level"> (only numbers)<br>
Player Name <input type="text" name="name2"><br><input type="submit" value="OK" name="ok3"></form>';}
//others form
if($_GET["action"]=="others"){ 
$main_content .= '<form action="?subtopic=admin" method="post">
Health <input type="text" name="health"> (only numbers)<br>
Mana <input type="text" name="mana"> (only numbers)<br>
Cap <input type="text" name="cap"> (only numbers)<br>
MagLevel <input type="text" name="maglevel"> (only numbers)<br>
Player Name <input type="text" name="name3"><br><input type="submit" value="OK" name="ok4"></form>';}
//delete form
if($_GET["action"]=="delete"){ 
$main_content .= '<form action="?subtopic=admin" method="post">
Player Name <input type="text" name="name4"><br><b>ARE  YOU SURE?</b> <input type="submit" value="YES"  name="ok5"></form>';}
//pos form
if($_GET["action"]=="groupedit"){ 
$main_content .= '<form action="?subtopic=admin" method="post">
Group ID <input type="text"  name="newpos"><i>example(1-player,2-tutor,3-senior  tutor,4-gamemaster,5-community manager,6-god)</i>
<br>
Player Name <input type="text" name="name9"><br><input type="submit" value="OK" name="ok10"></form>';}
/////////////ACCOUNT FORM////////////////
//account number form
if($_GET["action"]=="acc"){ 
$main_content .= '<form action="?subtopic=admin" method="post">
New account number <input type="text" name="newacc"> <br>
Player Name <input type="text" name="name5"><br><input type="submit" value="OK" name="ok6"></form>';}
//password form
if($_GET["action"]=="pass"){ 
$main_content .= '<form action="?subtopic=admin" method="post">
New password <input type="text" name="newpass"> <br>
Player Name <input type="text" name="name6"><br><input type="submit" value="OK" name="ok7"></form>';}
//mail form
if($_GET["action"]=="mail"){ 
$main_content .= '<form action="?subtopic=admin" method="post">
New e-mail adress <input type="text" name="newmail"> <br>
Player Name <input type="text" name="name7"><br><input type="submit" value="OK" name="ok8"></form>';}
//access form
if($_GET["action"]=="access"){ 
$main_content .= '<form action="?subtopic=admin" method="post">
Page access <input type="text" name="newaccess">(6->admin) <br>
Player Name <input type="text" name="name8"><br><input type="submit" value="OK" name="ok9"></form>';}
} else $main_content .= "You don't have required access!";
?>