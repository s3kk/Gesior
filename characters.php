<?PHP
$name = stripslashes(ucwords(strtolower(trim($_REQUEST['name']))));
if(empty($name))
$main_content .= 'Here you can get detailed information about a certain player on '.$config['server']['serverName'].'.<BR> <FORM ACTION="?subtopic=characters" METHOD=post><TABLE WIDTH=100% BORDER=0 CELLSPACING=1 CELLPADDING=4><TR><TD BGCOLOR="'.$config['site']['vdarkborder'].'" CLASS=white><B>Search Character</B></TD></TR><TR><TD BGCOLOR="'.$config['site']['darkborder'].'">
<TABLE BORDER=0 CELLPADDING=1>
<TR>
<TD>Name:</TD>
<TD><input name="name" maxlength="30" type="text" class="custom-field" value="" /></TD>
<TD><INPUT TYPE=image NAME="Submit" SRC="'.$layout_name.'/images/buttons/sbutton_submit.gif" BORDER=0 WIDTH=120 HEIGHT=18></TD>
</TR>
</TABLE></TD></TR></TABLE></FORM>';
elseif(($name == 'Sorcerer Sample') or ($name == 'Druid Sample') or ($name == 'Knight Sample') or ($name == 'Paladin Sample') or ($name == 'Account Manager')){
$main_content .= 'Here you can get detailed information about a certain player on '.$config['server']['serverName'].'.<BR>  <FORM ACTION="?subtopic=characters" METHOD=post><TABLE WIDTH=100% BORDER=0 CELLSPACING=1 CELLPADDING=4><TR><TD BGCOLOR="'.$config['site']['vdarkborder'].'" CLASS=white><B>Search Character</B></TD></TR><TR><TD BGCOLOR="'.$config['site']['darkborder'].'"><TABLE BORDER=0 CELLPADDING=1><TR><TD>Name:</TD><TD><INPUT NAME="name" VALUE=""SIZE=29 MAXLENGTH=29></TD><TD><INPUT TYPE=image NAME="Submit" SRC="'.$layout_name.'/images/buttons/sbutton_submit.gif" BORDER=0 WIDTH=120 HEIGHT=18></TD></TR></TABLE></TD></TR></TABLE></FORM>';
}
else
{
if(check_name($name)) 
{
$player = $ots->createObject('Player');
$player->find($name);
if($player->isLoaded()) 
{
$account = $player->getAccount();
$account_db = new OTS_Account();
if($config['site']['show_flag'])
{
$flagg = $account->getCustomField("flag");
$flag = '<image src="images/flags/'.$flagg.'.png" alt="" /> ';
}
$main_content .= '<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 WIDTH=100%><TR><TD></TD><TD><TABLE BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%><TR BGCOLOR='.$config['site']['vdarkborder'].'><TD COLSPAN=2 CLASS=white><B>Character Information</B></TD></TR>';
if(is_int($number_of_rows / 2)) { $bgcolor = $config['site']['darkborder']; } else { $bgcolor = $config['site']['lightborder']; } $number_of_rows++;
$main_content .= '<TR BGCOLOR="'.$bgcolor.'"><TD WIDTH=20%>Name:</TD><TD>'.$flag.'<font color="';
$main_content .= ($player->isOnline()) ? 'green' : 'red';
$main_content .= '"><b>'.$player->getName().'</b></font>';
if($player->isDeleted())
$main_content .= '<font color="red"> [DELETED]</font>';
if($player->isNameLocked())
$main_content .= '<font color="red"> [NAMELOCK]</font>';
$main_content .= '</TD></TR>';
if($player->getOldName())
{
if(is_int($number_of_rows / 2)) { $bgcolor = $config['site']['darkborder']; } else { $bgcolor = $config['site']['lightborder']; } $number_of_rows++;
if($player->isNameLocked())
$main_content .= '<TR BGCOLOR="'.$bgcolor.'"><TD>Proposition:</TD><TD>'.$player->getOldName().'</TD></TR>';
else
$main_content .= '<TR BGCOLOR="'.$bgcolor.'"><TD>Old name:</TD><TD>'.$player->getOldName().'</TD></TR>';
}
            $group = $player->getGroup();
if ($group == 2){$group_name = 'Tutor';}
if ($group == 3){$group_name = 'Senior Tutor';}
if ($group == 4){$group_name = 'Gamemaster';}
if ($group == 5){$group_name = 'Community Manager';}
            $rep = array(1 => '<img src="images/tutor-level-symbol.png" />', 2 => '<img src="images/tutor-level-symbol.png" /><img src="images/tutor-level-symbol.png" />', 3 => '<img src="images/tutor-level-symbol.png" /><img src="images/tutor-level-symbol.png" /><img src="images/tutor-level-symbol.png" />', 4 => '<img src="images/tutor-level-symbol.png" /><img src="images/tutor-level-symbol.png" /><img src="images/tutor-level-symbol.png" /><img src="images/tutor-level-symbol.png" />', 5 => '<img src="images/tutor-level-symbol.png" /><img src="images/tutor-level-symbol.png" /><img src="images/tutor-level-symbol.png" /><img src="images/tutor-level-symbol.png" /><img src="images/tutor-level-symbol.png" />', 6 => '<img src="images/tutor-level-symbol.png" /><img src="images/tutor-level-symbol.png" /><img src="images/tutor-level-symbol.png" /><img src="images/tutor-level-symbol.png" /><img src="images/tutor-level-symbol.png" /><img src="images/tutor-level-symbol.png" />');
if ($group == 6){$group_name = 'Administrator';}
if($group != 1)
{

if(is_int($number_of_rows / 2)) { $bgcolor = $config['site']['darkborder']; } else { $bgcolor = $config['site']['lightborder']; } $number_of_rows++;
$main_content .= '<TR BGCOLOR="'.$bgcolor.'"><TD>Position:</TD><TD>'.$group_name.'</TD></TR>';
}

if(is_int($number_of_rows / 2)) { $bgcolor = $config['site']['darkborder']; } else { $bgcolor = $config['site']['lightborder']; } $number_of_rows++;
$main_content .= '<TR BGCOLOR="'.$bgcolor.'"><TD>Sex:</TD><TD>';
$main_content .= ($player->getSex() == 0) ? 'female' : 'male';
$main_content .= '</TD></TR>';
if(is_int($number_of_rows / 2)) { $bgcolor = $config['site']['darkborder']; } else { $bgcolor = $config['site']['lightborder']; } $number_of_rows++;
$main_content .= '<TR BGCOLOR="'.$bgcolor.'"><TD>Vocation:</TD><TD>'.$vocation_name[$player->getWorld()][$player->getPromotion()][$player->getVocation()].'</TD></TR>';
if(is_int($number_of_rows / 2)) { $bgcolor = $config['site']['darkborder']; } else { $bgcolor = $config['site']['lightborder']; } $number_of_rows++;
$main_content .= '<TR BGCOLOR="'.$bgcolor.'"><TD>Level:</TD><TD>'.$player->getLevel().'</TD></TR>';
if(count($config['site']['worlds']) > 1)
{
if(is_int($number_of_rows / 2)) { $bgcolor = $config['site']['darkborder']; } else { $bgcolor = $config['site']['lightborder']; } $number_of_rows++;
$main_content .= '<TR BGCOLOR="'.$bgcolor.'"><TD>World:</TD><TD>'.$config['site']['worlds'][$player->getWorld()].'</TD></TR>';
}
if(!empty($towns_list[$player->getWorld()][$player->getTownId()]))
{
if(is_int($number_of_rows / 2)) { $bgcolor = $config['site']['darkborder']; } else { $bgcolor = $config['site']['lightborder']; } $number_of_rows++;
$main_content .= '<TR BGCOLOR="'.$bgcolor.'"><TD>Residence:</TD><TD>'.$towns_list[$player->getWorld()][$player->getTownId()].'</TD></TR>';
}
else
$main_content .= 'single</TD></TR>';
$house = $SQL->query( 'SELECT `houses`.`name`, `houses`.`town`, `houses`.`lastwarning` FROM `houses` WHERE `houses`.`world_id` = '.$player->getWorld().' AND `houses`.`owner` = '.$player->getId().';' )->fetchAll();
if(count($house) != 0)
{
if(is_int($number_of_rows / 2)) { $bgcolor = $config['site']['darkborder']; } else { $bgcolor = $config['site']['lightborder']; } $number_of_rows++;
$main_content .= '<TR BGCOLOR="'.$bgcolor.'"><TD>House:</TD><TD colspan="2">'.$house[0]['name'].' ('.$towns_list[$player->getWorld()][$house[0]['town']].') is paid until '.date("M j Y", $house[0]['lastwarning']).'</TD></TR>';
}
$rank_of_player = $player->getRank();
if(!empty($rank_of_player))
{
{
            $guild_id = $rank_of_player->getGuild()->getId();
            $guild_name = $rank_of_player->getGuild()->getName();
                if(is_int($number_of_rows / 2)) { $bgcolor = $config['site']['darkborder']; } else { $bgcolor = $config['site']['lightborder']; } $number_of_rows++;
                $main_content .= '<TR BGCOLOR="'.$bgcolor.'"><TD>Guild:</TD><TD>'.$rank_of_player->getName().' of the <a href="?subtopic=guilds&action=show&guild='.$guild_id.'">'.$guild_name.'</a></TD></TR>';
            }
            }
if(is_int($number_of_rows / 2)) { $bgcolor = $config['site']['darkborder']; } else { $bgcolor = $config['site']['lightborder']; } $number_of_rows++;
$lastlogin = $player->getLastLogin();
if(empty($lastlogin))
$main_content .= '<TR BGCOLOR="'.$bgcolor.'"><TD>Last login:</TD><TD colspan="2">Never logged in.</TD></TR>';
else
$main_content .= '<TR BGCOLOR="'.$bgcolor.'"><TD>Last login:</TD><TD colspan="2">'.date("j F Y, g:i a", $lastlogin).'</TD></TR>';
if($config['site']['show_creationdate'] == 1)
{
if(is_int($number_of_rows / 2)) { $bgcolor = $config['site']['darkborder']; } else { $bgcolor = $config['site']['lightborder']; } $number_of_rows++;
$main_content .= '<TR BGCOLOR="'.$bgcolor.'"><TD>Created:</TD><TD>'.date("j F Y, g:i a", $player->getCustomField("created")).'</TD></TR>';
}
$comment = $player->getCustomField("comment");
$newlines = array("\r\n", "\n", "\r");
$comment_with_lines = str_replace($newlines, '<br />', $comment, $count);
if($count < 50)
$comment = $comment_with_lines;
if(!empty($comment))
{
if(is_int($number_of_rows / 2)) { $bgcolor = $config['site']['darkborder']; } else { $bgcolor = $config['site']['lightborder']; } $number_of_rows++;
$main_content .= '<TR BGCOLOR="'.$bgcolor.'"><TD VALIGN=top>Comment:</TD><TD>'.$comment.'</TD></TR>';
}
if(is_int($number_of_rows / 2)) { $bgcolor = $config['site']['darkborder']; } else { $bgcolor = $config['site']['lightborder']; } $number_of_rows++;
$account_status .= ($account->isPremium()) ? '<font color="darkgreen"><b>Premium Account</b></font>' : 'Free Account';
$main_content .= '<TR BGCOLOR="'.$bgcolor.'"><TD>Account Status:</TD><TD>'.$account_status.'</TD></TR>';

// Show Statistics

// ** OUTFIT SHOWER -- fixed by Sekk
if(is_int($number_of_rows / 2)) { $bgcolor = $config['site']['darkborder']; } else { $bgcolor = $config['site']['lightborder']; } $number_of_rows++;
$main_content .= '<TD BGCOLOR="'.$bgcolor.'">Outfit:<TD style="background-color: '.$bgcolor.'"><image src="outfitter/outfitter.php?id='.$player->getLookType().'&addons='.$player->getLookAddons().'&head='.$player->getLookHead().'&body='.$player->getLookBody().'&legs='.$player->getLookLegs().'&feet='.$player->getLookFeet().'"/></TD></TD>';
$main_content .= '</TABLE>';
//END OUTFIT SHOWER

if($config['site']['show_signature']) {
// Signature by makr0mango.
function randomSignature( $folder ) {
$files = scandir ( "./$folder/" );
$signature = array();

foreach ( $files as $file ):
if ( substr ( strtolower ( $file ) , -4 ) == ".png" )
$signature[] = $file;
endforeach;

return rand(0,count($signature)-1);
}
$random = randomSignature("signatures");
$main_content .= '<br><tr></tr><tr></tr><tr></tr><TABLE BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%><TR BGCOLOR='.$config['site']['vdarkborder'].'><TD COLSPAN=2 CLASS=white><B>Signature</B></TD></TR>';
$main_content .= "<TR BGCOLOR=".$config['site']['darkborder']."><TD WIDTH=20%>Forum Link:</TD><TD><input type='text' size='75' onclick='this.select();' value='[url=\"http://" . $_SERVER['HTTP_HOST'] . "\"][IMG]http://" . $_SERVER['HTTP_HOST'] . "/signature.php?character=" .$player->getName(). "&image=" . $random . "[/IMG][/url]' /></TD></TR>";
$main_content .= "<TR BGCOLOR=".$config['site']['lightborder']."><TD WIDTH=20%>Direct Link:</TD><TD><input type='text' size='75' onclick='this.select();' value='http://" . $_SERVER['HTTP_HOST'] . "/signature.php?character=" .$player->getName(). "&image=" . $random . "' /></TD></TR>";
$main_content .= "<TR BGCOLOR=".$config['site']['darkborder']."><TD COLSPAN='2' style='text-align: center;'><img src='signature.php?character=" .$player->getName(). "&image=" . $random . "' /></TD></TR>";
$main_content .= '</TD></TR></TABLE>';
// Signature by makr0mango.
}
//modified status scripts by ballack13
$main_content .= '<table width=100%><tr>';
//equipment shower by ballack13
$id = $player->getCustomField("id");
$number_of_items = 1;
$main_content .= '<td align=center><table with=100% style="border: solid 1px #888888;" CELLSPACING="1"><TR>';
$list = array('2','1','3','6','4','5','9','7','10','8');
foreach ($list as $pid => $name) {
$top = $SQL->query('SELECT * FROM player_items WHERE player_id = '.$id.' AND pid = '.$list[$pid].';')->fetch();
if($top[itemtype] == false) {
if($list[$pid] == '8') {
$main_content .= '<td style="background-color: '.$config['site']['darkborder'].'; text-align: center;">Soul:<br/>'.$player->getSoul().'</td>';
}
if(is_int($number_of_items / 3)){
$main_content .= '<TD style="background-color: '.$config['site']['darkborder'].'; text-align: center;"><img src="images/items/'.$list[$pid].'.gif"/></TD></tr><tr>';
} else {
$main_content .= '<TD style="background-color: '.$config['site']['darkborder'].'; text-align: center;"><img src="images/items/'.$list[$pid].'.gif"/></TD>';
}
$number_of_items++;
}
else
{
if($list[$pid] == '8') {
$main_content .= '<td style="background-color: '.$config['site']['darkborder'].'; text-align: center;">Soul:<br/>'.$player->getSoul().'</td>';
}
if(is_int($number_of_items / 3))
$main_content .= '<TD style="background-color: '.$config['site']['darkborder'].'; text-align: center;"><img src="images/items/'.$top[itemtype].'.gif" width="45"/></TD></tr><tr>';
else
$main_content .= '<TD style="background-color: '.$config['site']['darkborder'].'; text-align: center;"><img src="images/items/'.$top[itemtype].'.gif" width="45"/></TD>';
$number_of_items++;
}
if($list[$pid] == '8') {
$main_content .= '<td style="background-color: '.$config['site']['darkborder'].'; text-align: center;">Cap:<br/>'.$player->getCap().'</td>';
}
}
$main_content .= '</tr></TABLE></td>';

//Hp/Mana/Exp Status by ballack13
$hp = ($player->getHealth() / $player->getHealthMax() * 100);
$main_content .= '<td align=center ><table width=100%><tr><td align=center><table CELLSPACING="1" CELLPADDING="4"><tr><td BGCOLOR="#D4C0A1" align="left" width="20%"><b>Player Health:</b></td>
<td BGCOLOR="#D4C0A1" align="left">'.$player->getHealth().'/'.$player->getHealthMax().'<div style="width: 100%; height: 3px; border: 1px solid #000;"><div style="background: red; width: '.$hp.'%; height: 3px;"></td></tr>';
if ($player->getManaMax() > 0) {
$mana = ($player->getMana() / $player->getManaMax() * 100);
$main_content .= '<tr><td BGCOLOR="#F1E0C6" align="left"><b>Player Mana:</b></td><td BGCOLOR="#F1E0C6" align="left">'.$player->getMana().'/'.$player->getManaMax().'<div style="width: 100%; height: 3px; border: 1px solid #000;"><div style="background: blue; width: '.$mana.'%; height: 3px;"></td>'; 
} else {
$main_content .= '<tr><td BGCOLOR="#F1E0C6" align="left"><b>Player Mana:</b></td><td BGCOLOR="#F1E0C6" align="left">0/0<div style="width: 100%; height: 3px; border: 1px solid #000;"><div style="background: blue; width: 100%; height: 3px;"></td>'; }
$main_content .= '</tr></table><tr>';
$next = ($player->getLevel() + 1);
$exp = ((50 / 3) * ($player->getLevel() * $player->getLevel() * $player->getLevel()) - (100 * ($player->getLevel() * $player->getLevel())) + ((850/3) * $player->getLevel()) - 200);
$expLeftPercent = max(0, min(100, ($player->getExperience() - $expCurrent) / ($expNext - $expCurrent) * 100));
$expnext = ((50 / 3) * ($next * $next * $next) - (100 * ($next * $next)) + ((850/3) * $next) - 200 - $player->getExperience());
$expresult = ($expnext / (($expnext + $player->getExperience()) - $exp) * 100);
$main_content .= '<tr><table CELLSPACING="1" CELLPADDING="4"><tr><td BGCOLOR="'.$config['site']['lightborder'].'" align="left" width="20%"><b>Player Level:</b></td><td BGCOLOR="'.$config['site']['lightborder'].'" align="left">'.$player->getLevel().'</td></tr>
<tr><td BGCOLOR="'.$config['site']['darkborder'].'" align="left"><b>Player Experience:</b></td><td BGCOLOR="'.$config['site']['darkborder'].'" align="left">'.$player->getExperience().' EXP.</td></tr>
<tr><td BGCOLOR="'.$config['site']['lightborder'].'" align="left"><b>To Next Level:</b></td><td BGCOLOR="'.$config['site']['lightborder'].'" align="left">You need <b>'.$expnext.' EXP</b> to Level <b>'.$next.'</b>.<div title="99.320604545 %" style="width: 100%; height: 3px; border: 1px solid #000;"><div style="background: red; width: '.$expLeftPercent.'%; height: 3px;"></td></tr></table></td></tr></table></tr></TABLE></td>';
if($config['site']['show_skills_info']) {
//Skills Pics v2. Table borders optimized by Absolute Mango
$main_content .= '<br/><table cellspacing="0" cellpadding="0" border="0" width="200" align="center"><caption><strong>Skills</strong></caption><tbody><tr>
<td align="center"><a href="?subtopic=highscores&list=experience"><img src="images/skills/level.png" style="border: none;"/></a></td>
<td align="center"><a href="?subtopic=highscores&list=magic"><img src="images/skills/ml.png" style="border: none;"/></a></td>
<td align="center"><a href="?subtopic=highscores&list=fist"><img src="images/skills/fist.png" style="border: none;"/></a></td>
<td align="center"><a href="?subtopic=highscores&list=club"><img src="images/skills/club.png" style="border: none;"/></a></td>
<td align="center"><a href="?subtopic=highscores&list=sword"><img src="images/skills/sword.png" style="border: none;"/></a></td>
<td align="center"><a href="?subtopic=highscores&list=axe"><img src="images/skills/axe.png" style="border: none;"/></a></td>
<td align="center"><a href="?subtopic=highscores&list=distance"><img src="images/skills/dist.png" style="border: none;"/></a></td>
<td align="center"><a href="?subtopic=highscores&list=shield"><img src="images/skills/def.png" style="border: none;"/></a></td>
<td align="center"><a href="?subtopic=highscores&list=fishing"><img src="images/skills/fish.png" style="border: none;"/></a></td>
</tr></tbody></table>
<table cellspacing="0" cellpadding="0" border="1" width="360" align="center"><tbody><tr><tr bgcolor="'.$config['site']['darkborder'].'">
<td align="center" width="38"><strong>Level</strong></td>
<td align="center" width="38"><strong>ML</strong></td>
<td align="center" width="42"><strong>Fist</strong></td>
<td align="center" width="40"><strong>Club</strong></td>
<td align="center" width="38"><strong>Swrd</strong></td>
<td align="center" width="38"><strong>Axe</strong></td>
<td align="center" width="38"><strong>Dist</strong></td>
<td align="center" width="38"><strong>Shield</strong></td>
<td align="center" width="38"><strong>Fish</strong></td></font>
</tr>
<tr bgcolor="'.$config['site']['lightborder'].'">
<td align="center" width="38">'.$player->getLevel().'</td>
<td align="center" width="38">'.$player->getMagLevel().'</td>
<td align="center" width="38">'.$player->getSkill(0).'</td>
<td align="center" width="38">'.$player->getSkill(1).'</td>
<td align="center" width="38">'.$player->getSkill(2).'</td>
<td align="center" width="38">'.$player->getSkill(3).'</td>
<td align="center" width="38">'.$player->getSkill(4).'</td>
<td align="center" width="38">'.$player->getSkill(5).'</td>
<td align="center" width="38">'.$player->getSkill(6).'</td>
</tr></tbody></table><div table align="center">&nbsp;<br />&nbsp;</div>';
//skill script end
}

if($config['site']['showStatistic'])
{
$listaddon = array('128','129','130','131','132','133','134','135','136','137','138','139','140','141','142','143','144','145','146','147','148','149','150','151','152','153','154','155','158','159','251','252','266','268','269','270','273','278','279','288','289','302','324','325');
$lookadd = array('0','1','2','3');
foreach ($listaddon as $pid => $name)
{
foreach ($lookadd as $addo => $name) 
{
$addon1 = $SQL->query('SELECT * FROM players WHERE id = '.$player->getId().' AND looktype = '.$listaddon[$pid].' AND lookaddons = '.$lookadd[$addo].';')->fetch();
if($addon1[looktype] == true )
{
$finaddon = $addon1[looktype] + $addon1[lookaddons] * 300;
$playerOutfit .= '<img src="images/addons/'.$finaddon.'.gif"/>';
}
}
}
// Experience
$currentlevelexp = (50 * ($player->getLevel() - 1) * ($player->getLevel() - 1) * ($player->getLevel() - 1) - 150 * ($player->getLevel() - 1) * ($player->getLevel() - 1) + 400 * ($player->getLevel() - 1)) / 3;
$nextlevel = ($player->getLevel() + 1);
$nextLevelExp = (50 * ($player->getLevel()) * ($player->getLevel()) * ($player->getLevel()) - 150 * ($player->getLevel()) * ($player->getLevel()) + 400 * ($player->getLevel())) / 3;
$leveldifference = ($nextLevelExp - $currentlevelexp);
$partofcurrentexp = ($player->getExperience()-$currentlevelexp);
$expBarPercentage = (($partofcurrentexp / $leveldifference)*100); 
// Spent Mana
$constantMana = $vocationConstantMana[$player->getWorld()][$player->getVocation()];
$spentMana = 1600 * force($constantMana, $player->getMagLevel());
$spentManaPer = $player->getManaSpent() / $spentMana * 100;
// Equip
$number_of_items = 1;
$contentEquipment .= '<table with=100% style="background-image:url(\'images/equipment/bg.gif\'); border: solid 1px #888888;" CELLSPACING="1"><TR>';
$list = array('2','1','3','6','4','5','9','7','10','8'); 
foreach ($list as $pid => $name) 
{
$top = $SQL->query('SELECT * FROM player_items WHERE player_id = '.$player->getId().' AND pid = '.$list[$pid].';')->fetch(); 
if($top[itemtype] == false) 
{ 
if($list[$pid] == '8') 
{
$contentEquipment .= '<td></td>'; 
}
if(is_int($number_of_items / 3))
{
$contentEquipment .= '<TD style="text-align: center;"><img src="images/equipment/'.$list[$pid].'.gif" width="32" higth="32" ></TD></tr><tr>'; 
} else {
$contentEquipment .= '<TD style="text-align: center;"><img src="images/equipment/'.$list[$pid].'.gif" width="32" higth="32"></TD>'; 
}
$number_of_items++; 
} 
else 
{ 
if($list[$pid] == '8') 
{
$contentEquipment .= '<td></td>'; 
} 
if(is_int($number_of_items / 3))
$contentEquipment .= '<TD style="background-image:url(\'images/equipment/0.gif\'); text-align: center;"><img src="images/items/'.$top[itemtype].'.gif" width="32" higth="32"/></TD></tr><tr>'; 
else
$contentEquipment .= '<TD style="background-image:url(\'images/equipment/0.gif\'); text-align: center;"><img src="images/items/'.$top[itemtype].'.gif" width="32" higth="32"/></TD>';
$number_of_items++; 
} 
if($list[$pid] == '8') 
{
$contentEquipment .= '<td></td>'; 
} 
}
$contentEquipment .= '</TR></table>';
// Stamina
$staminaDefault = 151200000;
$staminaPlayer = $player->getCustomField("stamina");
function getTime($value)
{
$h = floor($value / 3600000);
$m = floor(($value - $h * 3600000) / 60000);
return $h.':'.$m;
}
if($staminaPlayer <= 50400000)
$colorbg = 'red';
elseif($staminaPlayer <= 144000000)
$colorbg = 'orange';
else
$colorbg = 'lime';
$stamminaPer = ($staminaPlayer / $staminaDefault) * 100;
$main_content .= '<br><table border=0 cellspacing=1 cellpadding=4 width=100%>
<tr bgcolor='.$config['site']['vdarkborder'].'>
<td align="left" colspan=4 class=white><B>Statistics</B></td>
</tr>
<tr bgcolor='.$config['site']['vdarkborder'].'>
<td align="left" class=white>Equipment</td>
<td align="left" class=white colspan=3>Skills</td>
</tr>';
if(is_int($number_of_rows / 2)) { $bgcolor = $config['site']['darkborder']; } else { $bgcolor = $config['site']['lightborder']; } $number_of_rows++;
$main_content .= '<tr bgcolor='.$bgcolor.'>
<td align="center" rowspan=5 width=30%>'.$contentEquipment.'</td>
<td width=15%>Level:</td>
<td>
'.$player->getLevel().', '.$player->getExperience().' of '.$nextLevelExp.'
<div title="'.number_format($expBarPercentage, 0).'%" style="width: 100%; height: 3px; border: 1px solid #000;"><div style="background: green; width: '.$expBarPercentage.'%; height: 3px;">
</td>
<td rowspan=2 width="68" height="68" align="right" valign="bottom">'.$playerOutfit.'</td>
</tr>';
if(is_int($number_of_rows / 2)) { $bgcolor = $config['site']['darkborder']; } else { $bgcolor = $config['site']['lightborder']; } $number_of_rows++;
$main_content .= '<tr bgcolor='.$bgcolor.'>
<td>Magic Level:</td>
<td>
'.$player->getMagLevel().', '.$player->getManaSpent().' of '.$spentMana.'
<div title="'.number_format($spentManaPer,0).'%" style="width: 100%; height: 3px; border: 1px solid #000;"><div style="background: green; width: '.$spentManaPer.'%; height: 3px;">
</td>
</tr>';
$hp = ($player->getHealth() / $player->getHealthMax() * 100);
if(is_int($number_of_rows / 2)) { $bgcolor = $config['site']['darkborder']; } else { $bgcolor = $config['site']['lightborder']; } $number_of_rows++;
$main_content .= '<tr bgcolor='.$bgcolor.'>
<td>Hit Points:</td>
<td colspan=2>'.$player->getHealth().' of '.$player->getHealthMax().'<div title="'.number_format($hp,0).'%" style="width: 100%; height: 3px; border: 1px solid #000;"><div style="background: red; width: '.$hp.'%; height: 3px;"></td>
</tr>';
$mana = ($player->getMana() / $player->getManaMax() * 100);
if(is_int($number_of_rows / 2)) { $bgcolor = $config['site']['darkborder']; } else { $bgcolor = $config['site']['lightborder']; } $number_of_rows++;
$main_content .= '<tr bgcolor='.$bgcolor.'>
<td>Magic Points:</td>
<td colspan=2>'.$player->getMana().' of '.$player->getManaMax().'<div title="'.number_format($mana,0).'%" style="width: 100%; height: 3px; border: 1px solid #000;"><div style="background: blue; width: '.$mana.'%; height: 3px;"></td>
</tr>';
if(is_int($number_of_rows / 2)) { $bgcolor = $config['site']['darkborder']; } else { $bgcolor = $config['site']['lightborder']; } $number_of_rows++;
$main_content .= '<tr bgcolor='.$bgcolor.'>
<td>Stamina:</td>
<td colspan=2>
'.getTime($staminaPlayer).' of '.getTime($staminaDefault).'
<div title="'.number_format($stamminaPer,0).'%" style="width: 100%; height: 3px; border: 1px solid #000;"><div style="background: '.$colorbg.'; width: '.$stamminaPer.'%; height: 3px;">
</td>
</tr>';
$main_content .= '</table>';
}
// Show Statistics
if($config['site']['showAdvenceStatistic'])
{
$map = '<img src="generate.php?type=player&name='.urlencode($player->getName()).'">';
$main_content .= '<br><table border=0 cellspacing=1 cellpadding=4 width=100%>
<tr bgcolor='.$config['site']['vdarkborder'].'>
<td align="left" colspan=3 class=white><B>Advence statistics</B></td>
</tr>
<tr bgcolor='.$config['site']['vdarkborder'].'>
<td align="left" class=white>Maps</td>
<td align="left" class=white colspan=2>Skills</td>
</tr>';
if(is_int($number_of_rows / 2)) { $bgcolor = $config['site']['darkborder']; } else { $bgcolor = $config['site']['lightborder']; } $number_of_rows++;
$main_content .= '<tr bgcolor='.$bgcolor.'>
<td align="center" rowspan=7 width=30%>'.$map.'</td>
<td width=15%>Fist:</td><td>'.$player->getSkill(0).'</td>
</tr>';
if(is_int($number_of_rows / 2)) { $bgcolor = $config['site']['darkborder']; } else { $bgcolor = $config['site']['lightborder']; } $number_of_rows++;
$main_content .= '<tr bgcolor='.$bgcolor.'>
<td width=15%>Club:</td><td>'.$player->getSkill(1).'</td>
</tr>';
if(is_int($number_of_rows / 2)) { $bgcolor = $config['site']['darkborder']; } else { $bgcolor = $config['site']['lightborder']; } $number_of_rows++;
$main_content .= '<tr bgcolor='.$bgcolor.'>
<td width=15%>Sword:</td><td>'.$player->getSkill(2).'</td>
</tr>';
if(is_int($number_of_rows / 2)) { $bgcolor = $config['site']['darkborder']; } else { $bgcolor = $config['site']['lightborder']; } $number_of_rows++;
$main_content .= '<tr bgcolor='.$bgcolor.'>
<td width=15%>Axe:</td><td>'.$player->getSkill(3).'</td>
</tr>';
if(is_int($number_of_rows / 2)) { $bgcolor = $config['site']['darkborder']; } else { $bgcolor = $config['site']['lightborder']; } $number_of_rows++;
$main_content .= '<tr bgcolor='.$bgcolor.'>
<td width=15%>Distance:</td><td>'.$player->getSkill(4).'</td>
</tr>';
if(is_int($number_of_rows / 2)) { $bgcolor = $config['site']['darkborder']; } else { $bgcolor = $config['site']['lightborder']; } $number_of_rows++;
$main_content .= '<tr bgcolor='.$bgcolor.'>
<td width=15%>Shielding:</td><td>'.$player->getSkill(5).'</td>
</tr>';
if(is_int($number_of_rows / 2)) { $bgcolor = $config['site']['darkborder']; } else { $bgcolor = $config['site']['lightborder']; } $number_of_rows++;
$main_content .= '<tr bgcolor='.$bgcolor.'>
<td width=15%>Fishing:</td><td>'.$player->getSkill(6).'</td>
</tr>';
$main_content .= '</table>';
}
// Quest list show
if($config['site']['showQuests'])
{
$quests = $config['site']['quests'];
$questCount = count($quests);
$questCountDone = 0;
foreach($quests as $storage => $name) 
{
if(is_int($number_of_rows / 2)) { $bgcolor = $config['site']['darkborder']; } else { $bgcolor = $config['site']['lightborder']; } $number_of_rows++;
$quest = $SQL->query('SELECT * FROM player_storage WHERE player_id = '.$player->getId().' AND `key` = '.$quests[$storage].';')->fetch();
$questList .= '<TR bgcolor="'.$bgcolor.'"><TD WIDTH=98%>'.$storage.'</TD>';
if($quest == false) 
{
$questList .= '<TD><img src="images/false.gif"/></TD></TR>';
}
else
{
$questList .= '<TD><img src="images/true.gif"/></TD></TR>';
$questCountDone++;
}
}
$ilosc_procent = ( $questCountDone / $questCount ) * 100;
$questComplet .= '<tr bgcolor='.$bgcolor.'><td colspan=2><table width=100%><tr><td width=50%><b>Quest Complet</b>: '.round($ilosc_procent, 0).'%</td><td><div title="'.round($ilosc_procent, 0).'%" style="width: 100%; height: 3px; border: 1px solid #000;"><div style="background: green; width: '.$ilosc_procent.'%; height: 3px;"></td></tr></table>
</td></tr>';
$main_content .= '<BR><TABLE BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%><TR bgcolor='.$config['site']['vdarkborder'].'><TD align="left" COLSPAN=2 CLASS=white><B>Quests</B></TD></TR>'.$questComplet.''.$questList.'</TABLE>';
}
// Vip List show
if($config['site']['showVipList'])
{
// Table player_viplist: player_id, vip_id
// Table account_viplist: account_id, world_id, player_id
$vip = 0;
if($config['server']['separateVipListPerCharacter'] == false)
$vipLists = $SQL->query('SELECT * FROM `account_viplist` WHERE `account_id` = '.$account->getId().';');
else
$vipLists = $SQL->query('SELECT * FROM `player_viplist` WHERE `player_id` = '.$player->getId().';');
foreach($vipLists as $vipList) 
{
if($config['server']['separateVipListPerCharacter'] == false)
$result = $SQL->query('SELECT * FROM `players` WHERE `id` = '.$vipList['player_id'].';');
else
$result = $SQL->query('SELECT * FROM `players` WHERE `id` = '.$vipList['vip_id'].';');
foreach($result as $listVip)
{
$vip++;
if($config['site']['show_flag'])
{
$accounts = $SQL->query('SELECT * FROM accounts WHERE id = '.$listVip['account_id'].'')->fetch();
$flags = '<image src="images/flags/'.$accounts['flag'].'.png" alt=""/> ';
}
if(is_int($number_of_rows / 2)) { $bgcolor = $config['site']['darkborder']; } else { $bgcolor = $config['site']['lightborder']; } $number_of_rows++;
$vipResult .= '<tr bgcolor='.$bgcolor.'>
<td>'.$vip.'</td>
<td>
'.$flags.'<a href="index.php?subtopic=characters&name='.urlencode($listVip['name']).'">'.$listVip['name'].'</a>';
if($config['site']['showMoreInfo'])
$vipResult .= '<br><small>Level: '.$listVip['level'].', '.$vocation_name[$listVip['world_id']][$listVip['promotion']][$listVip['vocation']].'</small>';
$vipResult .= '</td>
</tr>';
}
}
if($vip > 0)
$main_content .= '<br><table border=0 cellspacing=1 cellpadding=4 width=100%><TR bgcolor='.$config['site']['vdarkborder'].'><TD align="left" COLSPAN=2 CLASS=white><B>Vip List</B></TD></TR>'.$vipResult.'</table>';
}
// Deaths list
$deads = 0;
            $player_deaths = $SQL->query('SELECT `id`, `date`, `level` FROM `player_deaths` WHERE `player_id` = '.$player->getId().' ORDER BY `date` DESC LIMIT 0,10;');
foreach($player_deaths as $death)
{
if(is_int($number_of_rows / 2))
$bgcolor = $config['site']['darkborder']; else $bgcolor = $config['site']['lightborder'];
$number_of_rows++; $deads++;
$dead_add_content .= "<tr bgcolor=\"".$bgcolor."\">
<td width=\"20%\" align=\"center\">".date("j M Y, H:i", $death['date'])."</td>
<td>";
$killers = $SQL->query("SELECT environment_killers.name AS monster_name, players.name AS player_name, players.deleted AS player_exists FROM killers LEFT JOIN environment_killers ON killers.id = environment_killers.kill_id
LEFT JOIN player_killers ON killers.id = player_killers.kill_id LEFT JOIN players ON players.id = player_killers.player_id
WHERE killers.death_id = ".$SQL->quote($death['id'])." ORDER BY killers.final_hit DESC, killers.id ASC")->fetchAll();
$i = 0;
$count = count($killers);
foreach($killers as $killer)
{
$i++;
if(in_array($i, array(1, $count)))
$killer['monster_name'] = str_replace(array("an ", "a "), array("", ""), $killer['monster_name']);
if($killer['player_name'] != "")
{
if($i == 1)
$dead_add_content .= "Killed at level <b>".$death['level']."</b> by ";
else 
if($i == $count)
$dead_add_content .= " and by ";
else
$dead_add_content .= ", ";
if($killer['monster_name'] != "")
$dead_add_content .= $killer['monster_name']." summoned by ";
if($killer['player_exists'] == 0)
$dead_add_content .= "<a href=\"index.php?subtopic=characters&name=".urlencode($killer['player_name'])."\">";
$dead_add_content .= $killer['player_name'];
if($killer['player_exists'] == 0)
$dead_add_content .= "</a>";
}
else
{
if($i == 1)
$dead_add_content .= "Died at level <b>".$death['level']."</b> by ";
else 
if($i == $count)
$dead_add_content .= " and by ";
else
$dead_add_content .= ", ";
$dead_add_content .= $killer['monster_name'];
}
if($i == $count)
$dead_add_content .= ".";
}
$dead_add_content .= ".</td></tr>";
}
if($deads > 0)
$main_content .= '<BR><TABLE BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%><TR BGCOLOR='.$config['site']['vdarkborder'].'><TD COLSPAN=2 CLASS=white><B>Deaths</B></TD></TR>' . $dead_add_content . '</TABLE>';
// Show Victims/Frags
if($config['site']['showVictims'])
{
$player_frags = $SQL->query('SELECT `player_deaths`.*, `players`.`name`, `killers`.`unjustified` FROM `player_deaths` LEFT JOIN `killers` ON `killers`.`death_id` = `player_deaths`.`id` LEFT JOIN `player_killers` ON `player_killers`.`kill_id` = `killers`.`id` LEFT JOIN `players` ON `players`.`id` = `player_deaths`.`player_id` WHERE `player_killers`.`player_id` = '.$player->getId().' ORDER BY `date` DESC LIMIT 0,'.$config['site']['limitVictims'].';'); 
if(count($player_frags)) 
{
$frags = 0; 
foreach($player_frags as $frag) 
{
$frags++; 
if(is_int($number_of_rows / 2)) $bgcolor = $config['site']['darkborder']; else $bgcolor = $config['site']['lightborder'];
$number_of_rows++;
$frag_add_content .= '<tr bgcolor='.$bgcolor.'>
<td width=25% align=left>'.date("d.m.Y, H:i:s", $frag['date']).'</td>
<td>'.(($player->getSex() == 0) ? 'She' : 'He').' fragged <a href=index.php?subtopic=characters&name='.$frag[name].'>'.$frag[name].'</a> at level '.$frag[level].'';
$frag_add_content .= ' ('.(($frag['unjustified'] == 0) ? '<font size=2 color=green>Justified</font>' : '<font size=2 color=red>Unjustified</font>').').</td></tr>'; 
}
if($frags >= 1)
$main_content .= '<br><TABLE BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%><TR bgcolor='.$config['site']['vdarkborder'].'><TD>Victims</TD><td>Frags: '.$count($player_frags).'</td></TR>'.$frag_add_content.'</TABLE>'; 
}
}
// onther info
            if(!$player->getHideChar()) {
                $main_content .= '<TABLE BORDER=0><TR><TD></TD></TR></TABLE><TABLE BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%><TR BGCOLOR='.$config['site']['vdarkborder'].'><TD COLSPAN=2 CLASS=white><B>Account Information</B></TD></TR>';
                
                if($account->getRLName())
                {
                    if(is_int($number_of_rows / 2)) { $bgcolor = $config['site']['darkborder']; } else { $bgcolor = $config['site']['lightborder']; } $number_of_rows++;
                    $main_content .= '<TR BGCOLOR='.$bgcolor.'><TD WIDTH=20%>Real name:</TD><TD>'.$account->getRLName().'</TD></TR>';
                }
                else
                {
                    if(is_int($number_of_rows / 2)) { $bgcolor = $config['site']['darkborder']; } else { $bgcolor = $config['site']['lightborder']; } $number_of_rows++;
                    $main_content .= '<TR BGCOLOR='.$bgcolor.'><TD WIDTH=20%>Real name:</TD><TD>Not informed</TD></TR>';
                }
                
                if($account->getLocation())
                {
                    if(is_int($number_of_rows / 2)) { $bgcolor = $config['site']['darkborder']; } else { $bgcolor = $config['site']['lightborder']; } $number_of_rows++;
                    $main_content .= '<TR BGCOLOR='.$bgcolor.'><TD WIDTH=20%>Location:</TD><TD>'.$account->getLocation().'</TD></TR>';
                }
                else
                {
                if(is_int($number_of_rows / 2)) { $bgcolor = $config['site']['darkborder']; } else { $bgcolor = $config['site']['lightborder']; } $number_of_rows++;
                    $main_content .= '<TR BGCOLOR='.$bgcolor.'><TD WIDTH=20%>Location:</TD><TD>Not informed</TD></TR>';
                }

$main_content .= '</TABLE>';
$main_content .= '<BR><TABLE BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%><TR BGCOLOR='.$config['site']['vdarkborder'].'><TD COLSPAN=5 CLASS=white><B>Characters</B></TD></TR>
<TR BGCOLOR='.$config['site']['darkborder'].'><TD><B>Name</B></TD><TD><B>World</B></TD><TD><B>Level</B></TD><TD><b>Status</b></TD><TD><B>&#160;</B></TD></TR>';
$account_players = $account->getPlayersList();
$account_players->orderBy('name');
$player_number = 0;
foreach($account_players as $player_list)
{
if(!$player_list->getHideChar())
{
$player_number++;
if(is_int($player_number / 2))
$bgcolor = $config['site']['darkborder'];
else
$bgcolor = $config['site']['lightborder'];
if(!$player_list->isOnline())
$player_list_status = '<font color="red">Offline</font>';
else
$player_list_status = '<font color="green">Online</font>';
$main_content .= '<TR BGCOLOR="'.$bgcolor.'"><TD WIDTH=52%><NOBR>'.$player_number.'.&#160;'.$player_list->getName();
$main_content .= ($player_list->isDeleted()) ? '<font color="red"> [DELETED]</font>' : '';
$main_content .= '</NOBR></TD><TD WIDTH=15%>'.$config['site']['worlds'][$player_list->getWorld()].'</TD><TD WIDTH=25%>'.$player_list->getLevel().' '.$vocation_name[$player_list->getWorld()][$player_list->getPromotion()][$player_list->getVocation()].'</TD><TD WIDTH="8%"><b>'.$player_list_status.'</b></TD><TD><TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0><FORM ACTION="?subtopic=characters" METHOD=post><TR><TD><INPUT TYPE=hidden NAME=name VALUE="'.$player_list->getName().'"><INPUT TYPE=image NAME="View '.$player_list->getName().'" ALT="View '.$player_list->getName().'" SRC="'.$layout_name.'/images/buttons/sbutton_view.gif" BORDER=0 WIDTH=120 HEIGHT=18></TD></TR></FORM></TABLE></TD></TR>';
}
}
$main_content .= '</TABLE>';
}
$main_content .= '<BR><BR><FORM ACTION="?subtopic=characters" METHOD=post><TABLE WIDTH=100% BORDER=0 CELLSPACING=1 CELLPADDING=4><TR><TD BGCOLOR="'.$config['site']['vdarkborder'].'" CLASS=white><B>Search Character</B></TD></TR><TR><TD BGCOLOR="'.$config['site']['darkborder'].'"><TABLE BORDER=0 CELLPADDING=1><TR><TD>Name:</TD><TD><INPUT NAME="name" VALUE=""SIZE=29 MAXLENGTH=29></TD><TD><INPUT TYPE=image NAME="Submit" SRC="'.$layout_name.'/images/buttons/sbutton_submit.gif" BORDER=0 WIDTH=120 HEIGHT=18></TD></TR></TABLE></TD></TR></TABLE></FORM>';
$main_content .= '</TABLE>';
}
else
$search_errors[] = 'Character <b>'.$name.'</b> does not exist.';
}
else
$search_errors[] = 'This name contains invalid letters. Please use only A-Z, a-z and space.';
if(!empty($search_errors))
{
$main_content .= '<div class="SmallBox" > <div class="MessageContainer" > <div class="BoxFrameHorizontal" style="background-image:url('.$layout_name.'/images/content/box-frame-horizontal.gif);" /></div> <div class="BoxFrameEdgeLeftTop" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></div> <div class="BoxFrameEdgeRightTop" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></div> <div class="ErrorMessage" > <div class="BoxFrameVerticalLeft" style="background-image:url('.$layout_name.'/images/content/box-frame-vertical.gif);" /></div> <div class="BoxFrameVerticalRight" style="background-image:url('.$layout_name.'/images/content/box-frame-vertical.gif);" /></div> <div class="AttentionSign" style="background-image:url('.$layout_name.'/images/content/attentionsign.gif);" /></div><b>The Following Errors Have Occurred:</b><br/>';
foreach($search_errors as $search_error)
$main_content .= '<li>'.$search_error;
$main_content .= '</div> <div class="BoxFrameHorizontal" style="background-image:url('.$layout_name.'/images/content/box-frame-horizontal.gif);" /></div> <div class="BoxFrameEdgeRightBottom" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></div> <div class="BoxFrameEdgeLeftBottom" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></div> </div></div><br/>';
$main_content .= '<BR><FORM ACTION="?subtopic=characters" METHOD=post><TABLE WIDTH=100% BORDER=0 CELLSPACING=1 CELLPADDING=4><TR><TD BGCOLOR="'.$config['site']['vdarkborder'].'" CLASS=white><B>Search Character</B></TD></TR><TR><TD BGCOLOR="'.$config['site']['darkborder'].'"><TABLE BORDER=0 CELLPADDING=1><TR><TD>Name:</TD><TD><INPUT NAME="name" VALUE=""SIZE=29 MAXLENGTH=29></TD><TD><INPUT TYPE=image NAME="Submit" SRC="'.$layout_name.'/images/buttons/sbutton_submit.gif" BORDER=0 WIDTH=120 HEIGHT=18></TD></TR></TABLE></TD></TR></TABLE></FORM>';
}
}
?>