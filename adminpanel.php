<?PHP
if($group_id_of_acc_logged >= $config['site']['access_admin_panel']) {
$main_content .= '<script type="text/javascript">

function showNewTickerForm()
{
document.getElementById("form").innerHTML = \'<form action="?subtopic=latestnews&action=newticker" method="post" ><table border="0" style="width: 100%;"><tr><td bgcolor="D4C0A1" align="center"><b>Select icon:</b></td><td><table border="0" bgcolor="F1E0C6"><tr><td><img src="images/news/icon_0.gif" width="20"></td><td><img src="images/news/icon_1.gif" width="20"></td><td><img src="images/news/icon_2.gif" width="20"></td><td><img src="images/news/icon_3.gif" width="20"></td><td><img src="images/news/icon_4.gif" width="20"></td></tr><tr><td><input type="radio" name="icon_id" value="0" checked="checked"></td><td><input type="radio" name="icon_id" value="1"></td><td><input type="radio" name="icon_id" value="2"></td><td><input type="radio" name="icon_id" value="3"></td><td><input type="radio" name="icon_id" value="4"></td></tr></table></td></tr><tr><td align="center" bgcolor="D4C0A1"><b>New<br>ticker<br>text:</b></td><td bgcolor="F1E0C6"><textarea name="new_ticker" rows="3" cols="45"></textarea></td></tr><tr><td><div class="BigButton" style="background-image:url('.$layout_name.'/images/buttons/sbutton.gif)" ><div onMouseOver="MouseOverBigButton(this);" onMouseOut="MouseOutBigButton(this);" ><div class="BigButtonOver" style="background-image:url('.$layout_name.'/images/buttons/sbutton_over.gif);" ></div><input class="ButtonText" type="image" name="Submit" alt="Submit" src="'.$layout_name.'/images/buttons/_sbutton_submit.gif" ></div></div></form><div class="BigButton" style="background-image:url('.$layout_name.'/images/buttons/sbutton.gif)" ><div onMouseOver="MouseOverBigButton(this);" onMouseOut="MouseOutBigButton(this);" ><div class="BigButtonOver" style="background-image:url('.$layout_name.'/images/buttons/sbutton_over.gif);" ></div><img class="ButtonText" id="AddTicker" src="'.$layout_name.'/images/buttons/_sbutton_cancel.gif" onClick="hide()" alt="AddTicker" /></div></div></td></tr></table>\';
}

function showNewNewsForm()
{
document.getElementById("form").innerHTML = \'<form action="?subtopic=latestnews&action=newnews" method="post" ><table border="0" style="width: 100%;"><tr><td bgcolor="D4C0A1" align="center"><b>Select icon:</b></td><td><table border="0" bgcolor="F1E0C6"><tr><td><img src="images/news/icon_0.gif" width="20"></td><td><img src="images/news/icon_1.gif" width="20"></td><td><img src="images/news/icon_2.gif" width="20"></td><td><img src="images/news/icon_3.gif" width="20"></td><td><img src="images/news/icon_4.gif" width="20"></td></tr><tr><td><input type="radio" name="icon_id" value="0" checked="checked"></td><td><input type="radio" name="icon_id" value="1"></td><td><input type="radio" name="icon_id" value="2"></td><td><input type="radio" name="icon_id" value="3"></td><td><input type="radio" name="icon_id" value="4"></td></tr></table></td></tr><tr><td align="center" bgcolor="F1E0C6"><b>Topic:</b></td><td><input type="text" name="news_topic" maxlenght="50" style="width: 300px" ></td></tr><tr><td align="center" bgcolor="D4C0A1"><b>News<br>text:</b></td><td bgcolor="F1E0C6"><textarea name="news_text" rows="6" cols="50"></textarea></td></tr><tr><td align="center" bgcolor="F1E0C6"><b>Your nick:</b></td><td><input type="text" name="news_name" maxlenght="40" style="width: 200px" ></td></tr><tr><td><div class="BigButton" style="background-image:url('.$layout_name.'/images/buttons/sbutton.gif)" ><div onMouseOver="MouseOverBigButton(this);" onMouseOut="MouseOutBigButton(this);" ><div class="BigButtonOver" style="background-image:url('.$layout_name.'/images/buttons/sbutton_over.gif);" ></div><input class="ButtonText" type="image" name="Submit" alt="Submit" src="'.$layout_name.'/images/buttons/_sbutton_submit.gif" ></div></div></form><div class="BigButton" style="background-image:url('.$layout_name.'/images/buttons/sbutton.gif)" ><div onMouseOver="MouseOverBigButton(this);" onMouseOut="MouseOutBigButton(this);" ><div class="BigButtonOver" style="background-image:url('.$layout_name.'/images/buttons/sbutton_over.gif);" ></div><img class="ButtonText" id="CancelAddNews" src="'.$layout_name.'/images/buttons/_sbutton_cancel.gif" onClick="hide()" alt="CancelAddNews" /></div></div></td></tr></table>\';
}

function showNewMailForm()
{
document.getElementById("form").innerHTML = \'<form action="?subtopic=lastestnews&action=massmail" method="post"><b>Subject Title:</b><input type="textbox" name="subject"><br><b>Mail Content:</b><textarea name="mail_content" rows="3" cols="45"></textarea><br><input type="submit" value="Send emails"></form>\'}

function hide()
{
document.getElementById("form").innerHTML = \'<center><b>Form place.</b></center>\';
}</script>';

if($action == "") {

$main_content .= '<table border="0" CELLPADDING=4 CELLSPACING=1 style="border: 1px solid black;margin: 0 auto;width: 80%;">
<TR BGCOLOR="'.$config['site']['vdarkborder'].'" style="color: white;">
<tr bgcolor=\'#505050\'><td colspan=\'2\' class=\'white\'><center><b>Message system</b></center></td></tr>

<tr bgcolor='.$config['site']['darkborder'].'>
<td style=\'text-align: center;\' colspan=\'2\'>Submit new <a onClick="showNewTickerForm()">ticker</a>, <a onClick="showNewNewsForm()">news</a> or send <a onClick="showNewMailForm()">email\'s</a>.</td></tr>
<tr bgcolor='.$config['site']['lightborder'].'>
	<td style=\'text-align: center;\' ><b><a href="?subtopic=latestnews">Edit / Delete</a></b> news.</td></tr>
</table>';


if($action == 'massmail'){

$email = $SQL->query('SELECT * FROM '.$SQL->tableName('accounts').' WHERE email != "";');
$mail_content = stripslashes(trim($_POST['mail_content']));
$subject = stripslashes(ucwords(strtolower(trim($_REQUEST['subject']))));
if(!empty($mail_content)) {
require("phpmailer/class.phpmailer.php");
$mail = new PHPMailer();
foreach($email  as $emails) {
if ($config['site']['smtp_enabled'] == "yes") {
$mail->IsSMTP();
$mail->Host = $config['site']['smtp_host'];
$mail->Port = (int)$config['site']['smtp_port'];
$mail->SMTPAuth = ($config['site']['smtp_auth'] ? true : false);
$mail->Username = $config['site']['smtp_user'];
$mail->Password = $config['site']['smtp_pass'];
} else
$mail->IsMail();
$mail->IsHTML(true);
$mail->From = $config['site']['mail_address'];
$mail->AddAddress($emails['email']);
$mail->Subject = $subject;
$mail->Body = $mail_content;
}
if($mail->Send())
{
$main_content .= '<br />Mass emails has been sent.';
} else {
$main_content .= '<br />An error occorred while sending email! Try again or contact with admin.';
}
}
}





$main_content .= '<div id="form" style="background: '.$config['site']['darkborder'].';border: 1px solid black;margin: 5px auto;width: 80%;padding: 5px;"><center><b>Form place.</b></center></div>';


$main_content .= '<table border="0" CELLPADDING=4 CELLSPACING=1 style="border: 1px solid black;margin: 0 auto;width: 80%;">
<TR BGCOLOR="'.$config['site']['vdarkborder'].'" style="color: white;">
<tr bgcolor=\'#505050\'><td colspan=\'2\' class=\'white\'><center><b>Other systems</b></center></td></tr>
<tr bgcolor='.$config['site']['lightborder'].'><td width="150"><b><a href="?subtopic=adminpanel&action=install_monsters">Reload Monsters</a></b></td><td><small><b>Load information about monsters from directory "/data/monsters/".</b></small> <b><small>[Delete old mosters configuration!]</small></b></td></tr>
<tr bgcolor='.$config['site']['darkborder'].'><td width="150"><b><a href="?subtopic=adminpanel&action=install_spells">Reload Spells</a></b></td><td><small><b>Load information about spells from file "/data/spells/spells.xml".</b></small><br/><b><small>[Delete old spells configuration!]</small></b></td></tr>
<tr bgcolor='.$config['site']['darkborder'].'><td width="150"><b><a href="?subtopic=adminpanel&action=npc_check_by_mappingfor&lng=en">Check NPCs</a> [EN]</b></td><td><b><small>Check items prices.</small></b></td></tr>
</table><br/>';
}


if(!empty($_GET['name']))
	{
		$name = $_GET['name'];
		$name_new = $_GET['name_new'];
		$player = new OTS_Player();
		$player->find($name);
		if($player->isLoaded() && $player->isNameLocked())
		{
			if($name_new == $player->getOldName())
			{
				if($action == 'accept')
				{
					$main_content .= '<font color="green">Changed name from <b>'.$player->getName().'</b> to <b>'.$player->getOldName().'</b>.</font>';
					$player->setCustomField('old_name', $player->getName());
					$player->setCustomField('name', $player->getOldName());
					$player->setCustomField('nick_verify', 1);
					$player->removeNameLock();
				}
				elseif($action == 'reject')
				{
					$main_content .= '<font color="green">Rejected proposition of change name from <b>'.$player->getName().'</b> to <b>'.$player->getOldName().'</b>.</font>';
					$player->setCustomField('old_name', '');
				}
			}
			else
				$main_content .= '<font color="red">Invalid new name. Try again.</font><br>';
		}
		else
			$main_content .= '<font color="red">Player with this name doesn\'t exist or isn\'t namelocked.</font><br>';
	}
	

$main_content .= '<table border="0" CELLPADDING=4 CELLSPACING=1 style="border: 1px solid black;margin: 0 auto;width: 80%;">
<TR BGCOLOR="'.$config['site']['vdarkborder'].'" style="color: white;"><td colspan=\'2\' class=\'white\'><center><b>First 50 namelocked players</b></center></td></tr>';
$main_content .= '<tr bgcolor="'.$config['site']['darkborder'].'"><td width="50%"><b>Nick proposition</b></td><td><b>Decision</b></td></tr>';
$number_of_rows = 1;
$players_info = $SQL->query("SELECT `players`.`name`, `players`.`old_name` AS `name_new` FROM `bans`, `players` WHERE `players`.`old_name` != '' AND `bans`.`value` = `players`.`id` AND `bans`.`active` = 1");
$players = array();

foreach($players_info->fetchAll() as $player)
{
	if(is_int($number_of_rows / 2)) { $bgcolor = $config['site']['darkborder']; } else { $bgcolor = $config['site']['lightborder']; } $number_of_rows++;
	$main_content .= '<tr bgcolor="'.$bgcolor.'"><td>'.$player['name_new'].'</td><td><a href="?subtopic=adminpanel&name='.urlencode($player['name']).'&name_new='.urlencode($player['name_new']).'"><font color="green">Accept</font></a> / <a href="?subtopic=namelock&action=reject&name='.urlencode($player['name']).'&name_new='.urlencode($player['name_new']).'"><font color="red">Reject</font></a><td></tr>';
}
$main_content .= '</table>';

	
	






//NPC CHECK by MappingFOR  // www.mayu11.webd.pl // PL & EN

if($action == "npc_check_by_mappingfor") {

$language['en']['none'] = "NONE";
$language['en']['name'] = "Name";
$language['en']['script'] = "Script";
$language['en']['mod_off'] = "The shop module is off.";
$language['en']['error_none'] = "<font color=\"red\"><b>ERROR!</b></font> NPC which has switched shop module should have at least one item to buy or sell.";
$language['en']['errors'] = "Now, I'll show You errors ;]";
$language['en']['error'] = "<center><small>I can buy item %s from %s for %s and sell at %s for %s. I'd like it :D</small></center>";
$language['en']['added'] = "Added as '%s' type.";
$language['en']['iit'] = "%s items in '%s' type.";
$language['en']['checked'] = "%s rows checked.";
$language['en']['no_erros'] = "<small>Like no errors here ;[</small>";

$language['pl']['none'] = "BRAK";
$language['pl']['name'] = "Nazwa";
$language['pl']['script'] = "Skrypt";
$language['pl']['mod_off'] = "Moduł sklepu jest wyłączony.";
$language['pl']['error_none'] = "<font color=\"red\">BŁĄD!</font> NPC z włączonym modułem sklepu powinien posiadać przynajmniej jeden przedmiot do kupienia lub sprzedania.";
$language['pl']['errors'] = "Teraz pokaże Ci błędy ;]";
$language['pl']['error'] = "<center><small>Mogę kupić przedmiot %s u %s za %s i sprzedać u %s za %s. Lubię to :D</small></center>";
$language['pl']['added'] = "Dodane jako typ '%s'.";
$language['pl']['iit'] = "%s przedmiotów w typie '%s'.";
$language['pl']['checked'] = "Sprawdzono %s linijek.";
$language['pl']['no_erros'] = "<small>Wygląda na to, że nie ma tutaj błędów ;[</small>";



$main_content .= '<TABLE BORDER=0 CELLSPACING=1 CELLPADDING=2 WIDTH=80% style="border: 1px solid black;margin: 0px auto;"><TR BGCOLOR='.$config['site']['vdarkborder'].'><TD align="left" COLSPAN=1 CLASS=white><B>Add Files</B></TD></TR>';
	// Magic cutting function by MappingFOR ("change_c($back_g,$bgcolor);" to use)
function change_c(&$back_g,&$bgcolor){
if(is_int($back_g / 2))
	$bgcolor = "#D4C0A1";
else
	$bgcolor = "#F1E0C6";
$back_g++;
}


if(isset($_GET['lng'])){$lng = $_GET['lng'];}else{$lng = 'pl';} // Change language
$scdost = $config['site']['server_path']."data/npc"; // data/npc Path

$a = 0;
$dir = opendir($scdost);

while(false !== ($file = readdir($dir)))
  if($file != '.' && $file != '..' && preg_match("([a-zA-Z0-9].xml)" , $file)){
change_c($back_g,$bgcolor);
$main_content .= '<tr BGCOLOR="'.$bgcolor.'"><td><small>+ ['.$scdost.'/'.$file.']</small></td></tr>';
$npcs[$a] = $file;
$a++;
}
$b = 0;

/* "Teraz nastąpi dodanie wszystkich możliwych do kupiienia i sprzedania przedmiotów. " */

$main_content .= '</table><br/><TABLE BORDER=0 CELLSPACING=1 CELLPADDING=2 WIDTH=80% style="border: 1px solid black;margin: 0px auto;"><TR BGCOLOR='.$config['site']['vdarkborder'].'><TD align="left" COLSPAN=1 CLASS=white><B>Raport</B></TD></TR>';


while($b + 1 != $a){ // Sprawdzenie wszystkich plików 
$infoXML = simplexml_load_file(''.$scdost.'\\'.$npcs[$b].''); // Wczytanie piku XML do zbadania.

$npc['name'] = $infoXML[name]; // Pobranie nazwy NPC
$npc['script'] = $infoXML[script]; // Pobranie skryptu który obsługuje

if(empty($npc['script']))
{
$npc['script'] = '<b>'.$language[$lng]['none'].'</b>';
}
else
{
$scripts[] = $infoXML[script];
}// Wczytanie do ogólnej zmiennej}

## Pobranie wszystkich możliwych parametrów.
$z = 0;
while(isset($infoXML->parameters->parameter[$z][key])){
$npc['param'][''.$infoXML->parameters->parameter[$z][key].''] = $infoXML->parameters->parameter[$z][value];
$z++;
}


## Jeśli modół sklepu jest włączony to pobierz wszystkie przedmioty.
if($npc['param']['module_shop'] == 1){

if(isset($npc['param']['shop_sellable'])){
## Wpisanie wszystkich przedmiotów, które można sprzedać.
$npc['sell_able'] = $npc['param']['shop_sellable'];
$npc['sell_able_array'] = explode(';',$npc['sell_able']);
$npc['sell_count'] = count($npc['sell_able_array']);

$c = 0;
while($npc['sell_able_array'][$c] != ""){
$npc['sell_able_array_positions'][$c] = explode(',',$npc['sell_able_array'][$c]);
$c++;
}
## Wyświetlenie informacji o tym, że nie ma żadnego przedmiotu który można sprzedać.
}else{$npc['sell_count'] = "None";}

if(isset($npc['param']['shop_buyable'])){
## Wpisanie wszystkich przedmiotów, które można kupić.
$npc['buy_able'] = $npc['param']['shop_buyable'];
$npc['buy_able_array'] = explode(';',$npc['buy_able']);
$npc['buy_count'] = count($npc['buy_able_array']);

$c = 0;
while($npc['buy_able_array'][$c] != ""){
$npc['buy_able_array_positions'][$c] = explode(',',$npc['buy_able_array'][$c]);
$c++;
}
## Wyświetlenie informacji o tym, że nie ma żadnego przedmiotu który można kupić.
}else{$npc['buy_count'] = "None";}



## Wyświetlenie informacji o tym, że NPC ma włączony "module_shop", a nie posiada żadnego przedmiotu do kupienia lub sprzedaży.
if($npc['buy_count'] == "None" AND $npc['sell_count'] == "None"){
$npc['shop'] = "<br/>".$language[$lng]['error_none']."";
}else{
$npc['shop'] = '['.$npc['buy_count'].' b;s '.$npc['sell_count'].']';
}

}else{
## Wyświetlenie informacji o tym, że NPC ma wyłączony "module_shop".
$npc['shop'] = $language[$lng]['mod_off'];
}

## Wyświetlenie informacji o NPC (Nazwa, Skrypt, Ilość sprzedawanych;kupowanych rzeczy.
change_c($back_g,$bgcolor);
$main_content .= '<tr BGCOLOR="'.$bgcolor.'"><td><small>'.$language[$lng]['name'].': '.$npc['name'].'; '.$language[$lng]['script'].': '.$npc['script'].'; '.$npc['shop'].'</small></td></tr>';

## Wyświetlanie listy przedmiotów do kupienia i sprzedania.
$c = 0;

while($npc['sell_able_array_positions'][$c] != ""){
change_c($back_g,$bgcolor);
$main_content .= '<tr BGCOLOR="'.$bgcolor.'"><td> * * * * * * * * * * * *- <small>';
$main_content .= print_r($npc['sell_able_array_positions'][$c],true);
$main_content .= ' [SELLABLE]</small></td></tr>';
$list['sell'][$npc['sell_able_array_positions'][$c][1]] = $npc['sell_able_array_positions'][$c][2];
$list['sell_npc'][$npc['sell_able_array_positions'][$c][1]] = $npc['name'];
$c++;
}
$c = 0;
while($npc['buy_able_array_positions'][$c] != ""){
change_c($back_g,$bgcolor);
$main_content .= '<tr BGCOLOR="'.$bgcolor.'"><td> * * * * * * * * * * * *- <small>';
$main_content .= print_r($npc['buy_able_array_positions'][$c],true);
$main_content .= ' [BUYALBE]</small></td></tr>';
$list['buy'][$npc['buy_able_array_positions'][$c][1]] = $npc['buy_able_array_positions'][$c][2];
$list['buy_npc'][$npc['buy_able_array_positions'][$c][1]] = $npc['name'];
$c++;
}

$npc = null;
$b++;
}

if(!empty($list['buy'])){
foreach($list['buy'] as $id => $price){
if($list['buy'][$id] < $list['sell'][$id] AND $list['buy'][$id] != 0){
change_c($back_g,$bgcolor);
$errors_items .= '<tr BGCOLOR="'.$bgcolor.'"><td>'.sprintf($language[$lng]['error'],$id,$list['buy_npc'][$id],$list['buy'][$id],$list['sell_npc'][$id],$list['sell'][$id]).'</td></tr>';
}
}
}

$main_content .= '</table><br/><TABLE BORDER=0 CELLSPACING=1 CELLPADDING=2 WIDTH=80% style="border: 1px solid black;margin: 0px auto;"><TR BGCOLOR='.$config['site']['vdarkborder'].'><TD align="left" COLSPAN=1 CLASS=white><B>[NEW] XML Results</B></TD></TR>';

if(!empty($errors_items)){
$main_content .= $errors_items;
}else{
change_c($back_g,$bgcolor);
$main_content .= '<tr BGCOLOR="'.$bgcolor.'"><td>'.$language[$lng]['no_erros'].'</td></tr>';
}


$main_content .= '</table><br/><TABLE BORDER=0 CELLSPACING=1 CELLPADDING=2 WIDTH=80% style="border: 1px solid black;margin: 0px auto;"><TR BGCOLOR='.$config['site']['vdarkborder'].'><TD align="left" COLSPAN=1 CLASS=white><B>[NEW] Lua Results</B></TD></TR>';

## LUA LOOKUP


$item = "";

$a = 0;
$b = 0;
$c = 0;


$item['buy']['addBuyableItem'] = array();
$item['sell']['addSellableItem'] = array();
$item['buy']['addBuyableItemContainer'] = array();

$dats = array();

foreach($scripts as $check){

if(preg_match("/scripts\//",$check)){
$dat = explode('scripts/',$check);
$dats[] = $dat[1];
}else{
$dats[] = $check;
}

}

while(is_file($scdost.'/scripts/'.$dats[$a])){

$content = file($scdost.'/scripts/'.$dats[$a]);

if(!empty($content))
foreach($content as $data)
{

if(preg_match("/shopModule:addBuyableItem\(/",$data) == true){
$item['buy']['addBuyableItem'][] = preg_replace("/shopModule:addBuyableItem({'(.*)'}, (.*), (.*), '(.*)')/i", "$1.$2.$3", $data).', '.$scripts[$a];
$lua_testing .= '<tr BGCOLOR="'.$bgcolor.'"><td><small>'.sprintf($language[$lng]['added'],'addBuyableItem').'</small></td></tr>';
$found = '<font color="green">[V]</font>';
}

if(preg_match("/shopModule:addSellableItem\(/",$data) == true){
$item['sell']['addSellableItem'][] = preg_replace("/shopModule:addSellableItem({'(.*)', '(.*)'}, (.*), (.*), '(.*)')/i", "$1.$2.$3.$4.$5", $data).', '.$scripts[$a];
$lua_testing .= '<tr BGCOLOR="'.$bgcolor.'"><td><small>'.sprintf($language[$lng]['added'],'addSellableItem').'</small></td></tr>';
$found = '<font color="green">[V]</font>';
}

if(preg_match("/shopModule:addBuyableItemContainer\(/",$data) == true){
$item['buy']['addBuyableItemContainer'][] = preg_replace("/shopModule:addBuyableItemContainer({'(.*)'}, (.*), (.*), (.*), '(.*)')/i", "$1", $data).', '.$scripts[$a];
$data .= '<tr BGCOLOR="'.$bgcolor.'"><td><small>'.sprintf($language[$lng]['added'],'addBuyableItemContainer').'</small></td></tr>';
$found = '<font color="green">[V]</font>';
}


change_c($back_g,$bgcolor);
$a++;
$lua_testing .= '<tr BGCOLOR="'.$bgcolor.'"><td><small>[FILE: '.$a.'][ROW: '.$b.']'.$found.' '.$data.'</small></td></tr>';
$a--;
$found = "";
$b++;
$c++;
}

$a++;
$b = 0;
}

if(is_array($item['buy']['addBuyableItem'])){
$count['addBuyableItem'] = count($item['buy']['addBuyableItem']);
}else{
$count['addBuyableItem'] = 0;
}


if(is_array($item['buy']['addSellableItem'])){
$count['addSellableItem'] = count($item['sell']['addSellableItem']);
}else{
$count['addSellableItem'] = 0;
}

if(is_array($item['buy']['addBuyableItemContainer'])){
$count['addBuyableItemContainer'] = count($item['buy']['addBuyableItemContainer']);
}else{
$count['addBuyableItemContainer'] = 0;
}



change_c($back_g,$bgcolor);
$main_content .= '<tr BGCOLOR="'.$bgcolor.'"><td><small>'.sprintf($language[$lng]['iit'],$count['addBuyableItem'],'addBuyableItem').'</small></td></tr>';
change_c($back_g,$bgcolor);
$main_content .= '<tr BGCOLOR="'.$bgcolor.'"><td><small>'.sprintf($language[$lng]['iit'],$count['addSellableItem'],'addSellableItem').'</small></td></tr>';
change_c($back_g,$bgcolor);
$main_content .= '<tr BGCOLOR="'.$bgcolor.'"><td><small>'.sprintf($language[$lng]['iit'],$count['addBuyableItemContainer'],'addBuyableItemContainer').'</small></td></tr>';
change_c($back_g,$bgcolor);
$main_content .= '<tr BGCOLOR="'.$bgcolor.'"><td><small>'.sprintf($language[$lng]['checked'],$c).'</small></td></tr>';

// Czas na ścięcie.

$list = "";
if(!empty($item['buy']['addBuyableItem']))
foreach($item['buy']['addBuyableItem'] as $data){
//shopModule:addBuyableItem({'WYPOWIEDŹ'}, ID, CENA, 'NAZWA')

$step = explode(', ', $data);
$id = $step[1];
$cena = $step[2];
$npc = $step[5];

$list['buy'][$id] = $cena;
$list['buy_npc'][$id] = $npc;
}
if(!empty($item['sell']['addSellableItem']))
foreach($item['sell']['addSellableItem'] as $data){
//shopModule:addSellableItem({'WYPOWIEDŹ', 'WYPOWIEDŹ'}, ID, CENA, 'NAZWA')

$step = explode(', ', $data);
$id = $step[2];
$cena = $step[3];
$npc = $step[5];

$list['sell'][$id] = $cena;
$list['sell_npc'][$id] = $npc;
}

if(!empty($item['buy']['addBuyableItemContainer']))
foreach($item['buy']['addBuyableItemContainer'] as $data){
$step = explode(', ', $data);
$id = $step[2];
$cena = $step[3] / 20;
$npc = $step[6];
$list['buy'][$id] = $cena;
$list['buy_npc'][$id] = $npc;
}

$errors_items = '';

if(!empty($list['buy']))
foreach($list['buy'] as $id => $price){
if($list['buy'][$id] < $list['sell'][$id] AND $list['buy'][$id] != 0){
change_c($back_g,$bgcolor);
$errors_items .= '<tr BGCOLOR="'.$bgcolor.'"><td>'.sprintf($language[$lng]['error'],$id,$list['buy_npc'][$id],$list['buy'][$id],$list['sell_npc'][$id],$list['sell'][$id]).'</td></tr>';
}
}


if(!empty($errors_items)){
$main_content .= $errors_items;
}else{
change_c($back_g,$bgcolor);
$main_content .= '<tr BGCOLOR="'.$bgcolor.'"><td>'.$language[$lng]['no_erros'].'</td></tr>';
}
$main_content .= '</table>';

## DO NOT DELETE FOOTER! // NIE USUWAĆ STOPKI!
$main_content .= '<p align="right"><small>Scripted by <b><a href="http://mayu11.webd.pl">MappingFOR</a> [<a href="http://otland.net/members/mappingfor/">OTLand Profile</a>]</b>. I <font size="5">♥</font> <a href="http://otland.net">OTLand.net</a></small></p>';
$main_content .= '<TABLE id="lua_report" BORDER=0 CELLSPACING=1 CELLPADDING=2 WIDTH=80% style="border: 1px solid black;margin: 0px auto;"><TR BGCOLOR='.$config['site']['vdarkborder'].'><TD align="left" COLSPAN=1 CLASS=white><B>[NEW] Lua raport</B></TD></TR>';
$main_content .= $lua_testing;
$main_content .= '</table>';
}
// END NPC CHECK



























//RELOAD CONFIGS
if($action == "reloadconfigs") {
$main_content .= '<center><h2>Load configurations</h2></center>Here you can choose what configuration you want to reload. It load configuration from OTS files.<br/><table style=\'clear:both\' border=0 cellpadding=0 cellspacing=0 width=\'100%\'>
<tr bgcolor='.$config['site']['darkborder'].'><td width="150"><font color="red"><b>Option</b></font></td><td><font color="red"><b>Description</b></font></td></tr>
<tr bgcolor='.$config['site']['lightborder'].'><td width="150"><b><a href="?subtopic=adminpanel&action=install_monsters">Reload Monsters</a></b></td><td><b>Load information about monsters from directory "your_server_path/data/monsters/". Delete old mosters configuration!</b></td></tr>
<tr bgcolor='.$config['site']['darkborder'].'><td width="150"><b><a href="?subtopic=adminpanel&action=install_spells">Reload Spells</a></b></td><td><b>Load information about spells from file "your_server_path/data/spells/spells.xml". Delete old spells configuration!</b></td></tr>
</table>';
$main_content .= '<br/><center><form action="?subtopic=adminpanel" METHOD=post><div class="BigButton" style="background-image:url('.$layout_name.'/images/buttons/sbutton.gif)" ><div onMouseOver="MouseOverBigButton(this);" onMouseOut="MouseOutBigButton(this);" ><div class="BigButtonOver" style="background-image:url('.$layout_name.'/images/buttons/sbutton_over.gif);" ></div><input class="ButtonText" type="image" name="Back" alt="Back" src="'.$layout_name.'/images/buttons/_sbutton_back.gif" ></div></div></form></center>';
}

//EDIT MONSTERS
if($action == "editmonsters") {

if($_REQUEST['todo'] == "setall") {
$visibility = $_REQUEST['visibility'];
if($visibility == "visible") {
try { $SQL->query('UPDATE z_monsters SET hide_creature = 0'); } catch(PDOException $error) {}
$main_content .= '<h3>All creatures are now VISIBLE!</h3>';
}
elseif($visibility == "hidden") {
try { $SQL->query('UPDATE z_monsters SET hide_creature = 1'); } catch(PDOException $error) {}
$main_content .= '<h3>All creatures are now HIDDEN!</h3>';
}
}

if($_REQUEST['todo'] == "editgfxlink") {
$monster_name = stripslashes($_REQUEST['monster']);
$new_link = stripslashes($_REQUEST['new_link']);
if(empty($_REQUEST['savenewgfxlink'])) {
$show_list = "no";
try { $monster = $SQL->query('SELECT * FROM z_monsters WHERE name = '.$SQL->quote($monster_name).';')->fetch(); } catch(PDOException $error) {}
$main_content .= '<center><h2>Edit link</h2></center><b>Link to image: </b>'.$config['server']['url'].'/monsters/<form action="?subtopic=adminpanel&action=editmonsters&todo=editgfxlink" method=POST><input type="hidden" name="savenewgfxlink" value="yes"><input type="hidden" name="monster" value="'.$monster_name.'"><input type="text" name="new_link" value="'.$monster['gfx_name'].'"><input type="submit" value="Save"></form>';
} else {
try { $SQL->query('UPDATE z_monsters SET gfx_name = '.$SQL->quote($new_link).' WHERE name = '.$SQL->quote($monster_name).';'); } catch(PDOException $error) {}
$main_content .= 'New link <b>'.$new_link.'</b> to <b>'.$monster_name.'</b> has been saved.<br/>';
}
}

if($_REQUEST['todo'] == "hide_monsters") {
$main_content .= '<center><h2>Hide monsters</h2></center>Monsters:<b>';
foreach($_REQUEST['hide_array'] as $monster_to_hide) {
$main_content .= '<li>'.$monster_to_hide;
try { $SQL->query('UPDATE z_monsters SET hide_creature = 1 WHERE name = '.$SQL->quote(stripslashes($monster_to_hide)).';'); } catch(PDOException $error) {}
}
$main_content .= '</b><br/>are now HIDDEN.';
}

if($_REQUEST['todo'] == "show_monsters") {
$main_content .= '<center><h2>Show monsters</h2></center>Monsters:<b>';
foreach($_REQUEST['show_array'] as $monster_to_hide) {
$main_content .= '<li>'.$monster_to_hide;
try { $SQL->query('UPDATE z_monsters SET hide_creature = 0 WHERE name = '.$SQL->quote(stripslashes($monster_to_hide)).';'); } catch(PDOException $error) {}
}
$main_content .= '</b><br/>are now VISIBLE.';
}

if($show_list != "no") {
//visible monsters list
try { $monsters = $SQL->query('SELECT * FROM z_monsters WHERE hide_creature != 1 ORDER BY name'); } catch(PDOException $error) {}
$main_content .= '<center><h2>Edit monsters</h2></center><h3>Visible monsters</h3><h4><a href="?subtopic=adminpanel&action=editmonsters&todo=setall&visibility=hidden">Set all monsters HIDDEN</a></h4><form action="?subtopic=adminpanel&action=editmonsters&todo=hide_monsters" method=POST><TABLE BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%><TR BGCOLOR='.$config['site']['vdarkborder'].'><TD CLASS=white width="200"><B><font CLASS=white>Photo</B></TD><TD CLASS=white width="200"><B><font CLASS=white>Edit photo</B></TD><TD CLASS=white width="200"><B><font CLASS=white>Name</B></TD><TD CLASS=white><B><font CLASS=white>Health</B></TD><TD CLASS=white><B><font CLASS=white>Experience</B></TD><TD CLASS=white><B><font CLASS=white>Hide Creature</B></TD></TR>';
foreach($monsters as $monster) {
if(is_int($number_of_rows / 2)) { $bgcolor = $config['site']['lightborder']; } else { $bgcolor = $config['site']['darkborder']; } $number_of_rows++;
$main_content .= '<TR BGCOLOR="'.$bgcolor.'"><TD>';
if(file_exists('monsters/'.$monster['gfx_name'])) {
$main_content .= '<img src="monsters/'.$monster['gfx_name'].'" height="40" width="40">';
} else {
$main_content .= '<img src="monsters/nophoto.png" height="40" width="40">';
}
$main_content .= '</TD><TD><a href="?subtopic=adminpanel&action=editmonsters&todo=editgfxlink&monster='.urlencode($monster['name']).'">Change image name</a></TD><TD>'.$monster['name'].'</TD><TD>'.$monster['health'].'</TD><TD>'.$monster['exp'].'</TD><TD><input type="checkbox" name="hide_array[]" value="'.$monster['name'].'"></TD>';
}
$main_content .= '<TR><TD></TD><TD></TD><TD></TD><TD>Hide</TD><TD>monsters:</TD><TD><input type="submit" value="Hide monsters"></TD></TR></TABLE></form>';

//hidden monsters list
try { $monsters = $SQL->query('SELECT * FROM z_monsters WHERE hide_creature != 0 ORDER BY name'); } catch(PDOException $error) {}
$main_content .= '<h3>Hidden monsters:</h3><h4><a href="?subtopic=adminpanel&action=editmonsters&todo=setall&visibility=visible">Set all monsters VISIBLE</a></h4><form action="?subtopic=adminpanel&action=editmonsters&todo=show_monsters" method=POST><TABLE BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%><TR BGCOLOR='.$config['site']['vdarkborder'].'><TD CLASS=white width="200"><B><font CLASS=white>Photo</B></TD><TD CLASS=white width="200"><B><font CLASS=white>Edit photo</B></TD><TD CLASS=white width="200"><B><font CLASS=white>Name</B></TD><TD CLASS=white><B><font CLASS=white>Health</B></TD><TD CLASS=white><B><font CLASS=white>Experience</B></TD><TD CLASS=white><B><font CLASS=white>Hide Creature</B></TD></TR>';
foreach($monsters as $monster) {
if(is_int($number_of_rows / 2)) { $bgcolor = $config['site']['lightborder']; } else { $bgcolor = $config['site']['darkborder']; } $number_of_rows++;
$main_content .= '<TR BGCOLOR="'.$bgcolor.'"><TD>';
if(file_exists('monsters/'.$monster['gfx_name'])) {
$main_content .= '<img src="monsters/'.$monster['gfx_name'].'" height="40" width="40">';
} else {
$main_content .= '<img src="monsters/nophoto.png" height="40" width="40">';
}
$main_content .= '</TD><TD><a href="?subtopic=adminpanel&action=editmonsters&todo=editgfxlink&monster='.$monster['name'].'">Change image name</a></TD><TD>'.$monster['name'].'</TD><TD>'.$monster['health'].'</TD><TD>'.$monster['exp'].'</TD><TD><input type="checkbox" name="show_array[]" value="'.$monster['name'].'"></TD>';
}
$main_content .= '<TR><TD></TD><TD></TD><TD></TD><TD>Show</TD><TD>monsters:</TD><TD><input type="submit" value="Show monsters"></TD></TR></TABLE></form>';
}
$main_content .= '<center><form action="?subtopic=adminpanel" METHOD=post><div class="BigButton" style="background-image:url('.$layout_name.'/images/buttons/sbutton.gif)" ><div onMouseOver="MouseOverBigButton(this);" onMouseOut="MouseOutBigButton(this);" ><div class="BigButtonOver" style="background-image:url('.$layout_name.'/images/buttons/sbutton_over.gif);" ></div><input class="ButtonText" type="image" name="Back" alt="Back" src="'.$layout_name.'/images/buttons/_sbutton_back.gif" ></div></div></form></center>';
}

//EDIT SPELLS
if($action == "editspells") {
if(!empty($_REQUEST['allspells'])) {
if($_REQUEST['allspells'] == 'visible') {
try { $SQL->query('UPDATE z_spells SET hide_spell = "0"'); } catch(PDOException $error) {}
$main_content .= 'All spells are now <b>visible</b>!';
}
elseif($_REQUEST['allspells'] == 'hidden') {
try { $SQL->query('UPDATE z_spells SET hide_spell = "1"'); } catch(PDOException $error) {}
$main_content .= 'All spells are now <b>hidden</b>!';
}
}
if($_REQUEST['savespell'] == "yes") {
if(!empty($_REQUEST['spell_name'])) {
if($_REQUEST['visible'] == "yes") {
try { $SQL->query('UPDATE z_spells SET hide_spell = 0 WHERE name = "'.$_REQUEST['spell_name'].'"'); } catch(PDOException $error) {}
$main_content .= "<b>'".$_REQUEST['spell_name']."'</b> is now VISIBLE!";
}
if($_REQUEST['visible'] == "no") {
try { $SQL->query('UPDATE z_spells SET hide_spell = "1" WHERE name = "'.$_REQUEST['spell_name'].'"'); } catch(PDOException $error) {}
$main_content .= "<b>'".$_REQUEST['spell_name']."'</b> is now HIDDEN!";
}
}
}
try { $spells = $SQL->query('SELECT * FROM z_spells ORDER BY name'); } catch(PDOException $error) {}
$main_content .= '<FORM ACTION="?subtopic=adminpanel&action=editspells" METHOD=post><input type="hidden" name="savespell" value="yes">
<TABLE BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%>
<TR BGCOLOR='.$config['site']['vdarkborder'].'><TD CLASS=white><B>Set spell visible or hidden</B></TD></TR>
<TR BGCOLOR='.$config['site']['darkborder'].'><TD><b>Spell: </b><SELECT NAME="spell_name">';
foreach($spells as $spell) {
$main_content .= '<OPTION VALUE="'.$spell['name'].'">'.$spell['name'];
if($spell['hide_spell'] == 1) {
$main_content .= ' (hidden)';
} else {
$main_content .= ' (visible)';
}
}
$main_content .= '</SELECT><b>Visible:</b> Yes<input type="radio" name="visible" value="yes" />No<input type="radio" name="visible" value="no" />&nbsp;&nbsp;&nbsp;<INPUT TYPE=image NAME="Submit" ALT="Submit" SRC="'.$layout_name.'/images/buttons/sbutton_submit.gif" BORDER=0 WIDTH=120 HEIGHT=18></TD><TR>
</TABLE></FORM>';
//show visible spells
$main_content .= '<h3>Visible spells list:</h3><a href="?subtopic=adminpanel&action=editspells&allspells=hidden">Set all spells: HIDDEN</a><TABLE BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%><TR BGCOLOR='.$config['site']['vdarkborder'].'><TD><B><font CLASS=white>Name</font></B></TD><TD><B><font CLASS=white>Sentence</font></a></B></TD><TD><B><font CLASS=white>Type<br/>(count)</font></B></TD><TD><B><font CLASS=white>Mana</font></B></TD><TD><B><font CLASS=white>Exp.<br/>Level</font></B></TD><TD><B><font CLASS=white>Magic<br/>Level</font></B></TD><TD><B><font CLASS=white>Soul</font></B></TD><TD CLASS=white><B>Need<br/>PACC?</B></TD></TR>';
try { $spells = $SQL->query('SELECT * FROM z_spells ORDER BY name'); } catch(PDOException $error) {}
foreach($spells as $spell) {
if($spell['hide_spell'] == "0") {
if(is_int($number_of_rows / 2)) { $bgcolor = $config['site']['lightborder']; } else { $bgcolor = $config['site']['darkborder']; } $number_of_rows++;
$main_content .= '<TR BGCOLOR="'.$bgcolor.'"><TD>'.$spell['name'].'</TD><TD>'.$spell['spell'].'</TD>';
if($spell['spell_type'] == 'conjure') {
$main_content .= '<TD>'.$spell['spell_type'].'('.$spell['conj_count'].')</TD>';
}
else
{
$main_content .= '<TD>'.$spell['spell_type'].'</TD>';
}
$main_content .= '<TD>'.$spell['mana'].'</TD><TD>'.$spell['lvl'].'</TD><TD>'.$spell['mlvl'].'</TD><TD>'.$spell['soul'].'</TD><TD>'.$spell['pacc'].'</TD></TR>';
}
}
$main_content .= '</TABLE>';
//show hidden spells
$main_content .= '<h3>Hidden spells list:</h3><a href="?subtopic=adminpanel&action=editspells&allspells=visible">Set all spells: VISIBLE</a><TABLE BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%><TR BGCOLOR='.$config['site']['vdarkborder'].'><TD><B><font CLASS=white>Name</font></B></TD><TD><B><font CLASS=white>Sentence</font></a></B></TD><TD><B><font CLASS=white>Type<br/>(count)</font></B></TD><TD><B><font CLASS=white>Mana</font></B></TD><TD><B><font CLASS=white>Exp.<br/>Level</font></B></TD><TD><B><font CLASS=white>Magic<br/>Level</font></B></TD><TD><B><font CLASS=white>Soul</font></B></TD><TD CLASS=white><B>Need<br/>PACC?</B></TD></TR>';
try { $spells = $SQL->query('SELECT * FROM z_spells ORDER BY name'); } catch(PDOException $error) {}
foreach($spells as $spell) {
if($spell['hide_spell'] == "1") {
if(is_int($number_of_rows / 2)) { $bgcolor = $config['site']['lightborder']; } else { $bgcolor = $config['site']['darkborder']; } $number_of_rows++;
$main_content .= '<TR BGCOLOR="'.$bgcolor.'"><TD>'.$spell['name'].'</TD><TD>'.$spell['spell'].'</TD>';
if($spell['spell_type'] == 'conjure') {
$main_content .= '<TD>'.$spell['spell_type'].'('.$spell['conj_count'].')</TD>';
}
else
{
$main_content .= '<TD>'.$spell['spell_type'].'</TD>';
}
$main_content .= '<TD>'.$spell['mana'].'</TD><TD>'.$spell['lvl'].'</TD><TD>'.$spell['mlvl'].'</TD><TD>'.$spell['soul'].'</TD><TD>'.$spell['pacc'].'</TD></TR>';
}
}
$main_content .= '</TABLE><br/><center><form action="?subtopic=adminpanel" METHOD=post><div class="BigButton" style="background-image:url('.$layout_name.'/images/buttons/sbutton.gif)" ><div onMouseOver="MouseOverBigButton(this);" onMouseOut="MouseOutBigButton(this);" ><div class="BigButtonOver" style="background-image:url('.$layout_name.'/images/buttons/sbutton_over.gif);" ></div><input class="ButtonText" type="image" name="Back" alt="Back" src="'.$layout_name.'/images/buttons/_sbutton_back.gif" ></div></div></form></center>';
}

//INSTALL MONSTERS
if($action == "install_monsters") {
try { $SQL->query("DELETE FROM ".$SQL->tableName('z_monsters').";"); } catch(PDOException $error) {}
$main_content .= '<h2>Reload monsters.</h2>';
$main_content .= '<h2>All records deleted from table \'z_monsters\' in database.</h2>';

$main_content .= "IMPORT SQL -> monsters.txt";

$main_content .= '<center><form action="?subtopic=adminpanel" METHOD=post><div class="BigButton" style="background-image:url('.$layout_name.'/images/buttons/sbutton.gif)" ><div onMouseOver="MouseOverBigButton(this);" onMouseOut="MouseOutBigButton(this);" ><div class="BigButtonOver" style="background-image:url('.$layout_name.'/images/buttons/sbutton_over.gif);" ></div><input class="ButtonText" type="image" name="Back" alt="Back" src="'.$layout_name.'/images/buttons/_sbutton_back.gif" ></div></div></form></center>';
}

//SPELLS
if($action == "install_spells") {
try { $SQL->query('DELETE FROM '.$SQL->tableName('z_spells').';'); } catch(PDOException $error) {}
$main_content .= '<h2>Reload spells.</h2>';
$main_content .= '<h2>All records deleted from table \'z_spells\' in database.</h2>';
    foreach($vocation_name[0] as $prom => $arr)
      foreach($arr as $voc_id => $voc_name)
  $vocations_ids[$voc_name] = $voc_id;
$allspells = new OTS_SpellsList($config['site']['server_path']."data/spells/spells.xml");
//add conjure spells
$conjurelist = $allspells->getConjuresList();
$main_content .= "<h3>Conjure:</h3>";
foreach($conjurelist as $spellname)
{
  $spell = $allspells->getConjure($spellname);
  $lvl = $spell->getLevel();
  $mlvl = $spell->getMagicLevel();
  $mana = $spell->getMana();
  $name = $spell->getName();
  $soul = $spell->getSoul();
  $spell_txt = $spell->getWords();
  $vocations = $spell->getVocations();


  $vocations_to_db = "";
  $voc_nr = 0;
  foreach($vocations as $vocation_to_add_name)
  {
    $voc_str = '';
    foreach($vocation_name[0] as $prom => $arr)
      foreach($arr as $voc_id => $voc_name)
        if($vocation_to_add_name == $voc_name)
          $voc_str = $prom.';'.$voc_id;
    if(!empty($voc_str))
    {
      $vocations_to_db .= $voc_str;
      $voc_nr++;
      if($voc_nr != count($vocations))
        $vocations_to_db .= ',';
    }
  }

  $enabled = $spell->isEnabled();
  if($enabled)
    $hide_spell = 0;
  else
    $hide_spell = 1;
  $pacc = $spell->isPremium();
  if($pacc)
    $pacc = 'yes';
  else
    $pacc = 'no';
  $type = 'conjure';
  $count = $spell->getConjureCount();
  try { $SQL->query('INSERT INTO '.$SQL->tableName('z_spells').' (name, spell, spell_type, mana, lvl, mlvl, soul, pacc, vocations, conj_count, hide_spell) VALUES ('.$SQL->quote($name).', '.$SQL->quote($spell_txt).', \''.$type.'\', \''.$mana.'\', \''.$lvl.'\', \''.$mlvl.'\', \''.$soul.'\', \''.$pacc.'\', '.$SQL->quote($vocations_to_db).', \''.$count.'\', \''.$hide_spell.'\')'); } catch(PDOException $error) {}
  $main_content .= "Added: ".$name."<br>";
}
//add instant spells
$instantlist = $allspells->getInstantsList();
$main_content .= "<h3>Instant:</h3>";
foreach($instantlist as $spellname)
{
$spell = $allspells->getInstant($spellname);
$lvl = $spell->getLevel();
$mlvl = $spell->getMagicLevel();
$mana = $spell->getMana();
$name = $spell->getName();
$soul = $spell->getSoul();
$spell_txt = $spell->getWords();
$vocations = $spell->getVocations();


  $vocations_to_db = "";
  $voc_nr = 0;
  foreach($vocations as $vocation_to_add_name)
  {
    $voc_str = '';
    foreach($vocation_name[0] as $prom => $arr)
      foreach($arr as $voc_id => $voc_name)
        if($vocation_to_add_name == $voc_name)
          $voc_str = $prom.';'.$voc_id;
    if(!empty($voc_str))
    {
      $vocations_to_db .= $voc_str;
      $voc_nr++;
      if($voc_nr != count($vocations))
        $vocations_to_db .= ',';
    }
  }


$enabled = $spell->isEnabled();
if($enabled) {
$hide_spell = 0;
}
else
{
$hide_spell = 1;
}
$pacc = $spell->isPremium();
if($pacc) {
$pacc = 'yes';
}
else
{
$pacc = 'no';
}
$type = 'instant';
$count = 0;
try { $SQL->query('INSERT INTO z_spells (name, spell, spell_type, mana, lvl, mlvl, soul, pacc, vocations, conj_count, hide_spell) VALUES ('.$SQL->quote($name).', '.$SQL->quote($spell_txt).', \''.$type.'\', \''.$mana.'\', \''.$lvl.'\', \''.$mlvl.'\', \''.$soul.'\', \''.$pacc.'\', '.$SQL->quote($vocations_to_db).', \''.$count.'\', \''.$hide_spell.'\')'); } catch(PDOException $error) {}
$main_content .= "Added: ".$name."<br/>";
}
$main_content .= '<center><form action="?subtopic=adminpanel" METHOD=post><div class="BigButton" style="background-image:url('.$layout_name.'/images/buttons/sbutton.gif)" ><div onMouseOver="MouseOverBigButton(this);" onMouseOut="MouseOutBigButton(this);" ><div class="BigButtonOver" style="background-image:url('.$layout_name.'/images/buttons/sbutton_over.gif);" ></div><input class="ButtonText" type="image" name="Back" alt="Back" src="'.$layout_name.'/images/buttons/_sbutton_back.gif" ></div></div></form></center>';
}

}
else
{
$main_content .= 'You don\'t have admin access.';
$main_content .= '<center><form action="" METHOD=post><div class="BigButton" style="background-image:url('.$layout_name.'/images/buttons/sbutton.gif)" ><div onMouseOver="MouseOverBigButton(this);" onMouseOut="MouseOutBigButton(this);" ><div class="BigButtonOver" style="background-image:url('.$layout_name.'/images/buttons/sbutton_over.gif);" ></div><input class="ButtonText" type="image" name="Back" alt="Back" src="'.$layout_name.'/images/buttons/_sbutton_back.gif" ></div></div></form></center>';
}
?>