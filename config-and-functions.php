<?PHP
// ###################### CONFIG ########################
//load page config file
$config['site'] = parse_ini_file('config/config.ini');
include('config/config.php');
//check install
if($config['site']['install'] != "no")
{
header("Location: install.php");
exit;
}
//load server config
$config['server'] = parse_ini_file($config['site']['server_path'].'config.lua');
if(isset($config['server']['mysqlHost']))
{
//new (0.2.6+) ots config.lua file
$mysqlhost = $config['server']['mysqlHost'];
$mysqluser = $config['server']['mysqlUser'];
$mysqlpass = $config['server']['mysqlPass'];
$mysqldatabase = $config['server']['mysqlDatabase'];
}
elseif(isset($config['server']['sqlHost']))
{
//old (0.2.4) ots config.lua file
$mysqlhost = $config['server']['sqlHost'];
$mysqluser = $config['server']['sqlUser'];
$mysqlpass = $config['server']['sqlPass'];
$mysqldatabase = $config['server']['sqlDatabase'];
}
$sqlitefile = $config['server']['sqliteDatabase'];
$passwordency = '';
if(strtolower($config['server']['encryptionType']) == 'md5')
$passwordency = 'md5';
if(strtolower($config['server']['encryptionType']) == 'sha1')
$passwordency = 'sha1';
// loads #####POT mainfile#####
include('pot/OTS.php');
// PDO and POT connects to database
$ots = POT::getInstance();
if(strtolower($config['server']['sqlType']) == "mysql")
{
//connect to MySQL database
try
{
$ots->connect(POT::DB_MYSQL, array('host' => $mysqlhost, 'user' => $mysqluser, 'password' => $mysqlpass, 'database' => $mysqldatabase) );
}
catch(PDOException $error)
{
echo 'Database error - can\'t connect to MySQL database. Possible reasons:<br>1. MySQL server is not running on host.<br>2. MySQL user, password, database or host isn\'t configured in: <b>'.$config['site']['server_path'].'config.lua</b> .<br>3. MySQL user, password, database or host is wrong.';
exit;
}
}
elseif(strtolower($config['server']['sqlType']) == "sqlite")
{
//connect to SQLite database
$link_to_sqlitedatabase = $config['site']['server_path'].$sqlitefile;
try
{
$ots->connect(POT::DB_SQLITE, array('database' => $link_to_sqlitedatabase));
}
catch(PDOException $error)
{
echo 'Database error - can\'t open SQLite database. Possible reasons:<br><b>'.$link_to_sqlitedatabase.'</b> - file isn\'t valid SQLite database.<br><b>'.$link_to_sqlitedatabase.'</b> - doesn\'t exist.<br><font color="red">Wrong PHP configuration. Default PHP does not work with SQLite databases!</font>';
exit;
}
}
else
{
echo 'Database error. Unknown database type in <b>'.$config['site']['server_path'].'config.lua</b> . Must be equal to: "<b>mysql</b>" or "<b>sqlite</b>". Now is: "<b>'.strtolower($config['server']['sqlType']).'"</b>';
exit;
}

$SQL = POT::getInstance()->getDBHandle();
$layout_name = "layouts/".$layout_name = $config['site']['layout'];;
$layout_ini = parse_ini_file($layout_name.'/layout_config.ini');
foreach($layout_ini as $key => $value)
$config['site'][$key] = $value;
//###################### FUNCTIONS ######################
function isPremium($premdays, $lastday)
{
return ($premdays - (date("z", time()) + (365 * (date("Y", time()) - date("Y", $lastday))) - date("z", $lastday)) > 0);
}
//save config in ini file
function saveconfig_ini($config)
{
$file = fopen("config/config.ini", "w");
foreach($config as $param => $data)
{
$file_data .= $param.' = "'.str_replace('"', '', $data).'"
';
}
rewind($file);
fwrite($file, $file_data);
fclose($file);
}
//return password to db
function password_ency($password)
{
$ency = $GLOBALS['passwordency'];
if($ency == 'sha1')
return sha1($password);
elseif($ency == 'md5')
return md5($password);
elseif($ency == '')
return $password;
}
//delete player with name
function delete_player($name) {
$SQL = $GLOBALS['SQL'];
$player = new OTS_Player();
$player->find($name);
if($player->isLoaded()) {
try { $SQL->query("DELETE FROM player_skills WHERE player_id = '".$player->getId()."';"); } catch(PDOException $error) {}
try { $SQL->query("DELETE FROM guild_invites WHERE player_id = '".$player->getId()."';"); } catch(PDOException $error) {}
try { $SQL->query("DELETE FROM player_items WHERE player_id = '".$player->getId()."';"); } catch(PDOException $error) {}
try { $SQL->query("DELETE FROM player_depotitems WHERE player_id = '".$player->getId()."';"); } catch(PDOException $error) {}
try { $SQL->query("DELETE FROM player_spells WHERE player_id = '".$player->getId()."';"); } catch(PDOException $error) {}
try { $SQL->query("DELETE FROM player_storage WHERE player_id = '".$player->getId()."';"); } catch(PDOException $error) {}
try { $SQL->query("DELETE FROM player_viplist WHERE player_id = '".$player->getId()."';"); } catch(PDOException $error) {}
try { $SQL->query("DELETE FROM player_deaths WHERE player_id = '".$player->getId()."';"); } catch(PDOException $error) {}
try { $SQL->query("DELETE FROM player_deaths WHERE killed_by = '".$player->getId()."';"); } catch(PDOException $error) {}
$rank = $player->getRank();
if(!empty($rank)) {
$guild = $rank->getGuild();
if($guild->getOwner()->getId() == $player->getId()) {
$rank_list = $guild->getGuildRanksList();
if(count($rank_list) > 0) {
$rank_list->orderBy('level');
foreach($rank_list as $rank_in_guild) {
$players_with_rank = $rank_in_guild->getPlayersList();
$players_with_rank->orderBy('name');
$players_with_rank_number = count($players_with_rank);
if($players_with_rank_number > 0) {
foreach($players_with_rank as $player_in_guild) {
$player_in_guild->setRank();
$player_in_guild->save();
}
}
$rank_in_guild->delete();
}
$guild->delete();
}
}
}
$player->delete();
return TRUE;
}
}

//delete guild with id
function delete_guild($id) {
$guild = new OTS_Guild();
$guild->load($id);
if($guild->isLoaded()) {
$rank_list = $guild->getGuildRanksList();
if(count($rank_list) > 0) {
$rank_list->orderBy('level');
foreach($rank_list as $rank_in_guild) {
$players_with_rank = $rank_in_guild->getPlayersList();
if(count($players_with_rank) > 0) {
foreach($players_with_rank as $player_in_guild) {
$player_in_guild->setRank();
$player_in_guild->save();
}
}
$rank_in_guild->delete();
}
}
$guild->delete();
return TRUE;
}
else
return FALSE;
}

//is it valid nick?
function check_name($name)//sprawdza name
{
$temp = strspn("$name", "qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM- [ ] '");
if ($temp != strlen($name)) {
return false;
}
else
{
$ok = "/[a-zA-Z ']{1,25}/";
return (preg_match($ok, $name))? true: false;
}
}

//is it valid nick?
function check_account_name($name)//sprawdza name
{
$temp = strspn("$name", "QWERTYUIOPASDFGHJKLZXCVBNM0123456789");
if ($temp != strlen($name))
return false;
if(strlen($name) > 32)
return false;
else
{
$ok = "/[A-Z0-9]/";
return (preg_match($ok, $name))? true: false;
}
}

//is it valid nick for new char?
function check_name_new_char($name)//sprawdza name
{
$name_to_check = strtolower($name);
//first word can't be:
//names blocked:
$names_blocked = array('gm','cm', 'god', 'tutor');
$first_words_blocked = array('gm ','cm ', 'god ','tutor ', "'", '-');
//name can't contain:
$words_blocked = array('gamemaster', 'game master', 'game-master', "game'master", '--', "''","' ", " '", '- ', ' -', "-'", "'-", 'fuck', 'sux', 'suck', 'noob', 'tutor');
foreach($first_words_blocked as $word)
if($word == substr($name_to_check, 0, strlen($word)))
return false;
if(substr($name_to_check, -1) == "'" || substr($name_to_check, -1) == "-")
return false;
if(substr($name_to_check, 1, 1) == ' ')
return false;
if(substr($name_to_check, -2, 1) == " ")
return false;
foreach($names_blocked as $word)
if($word == $name_to_check)
return false;
foreach($GLOBALS['config']['site']['monsters'] as $word)
if($word == $name_to_check)
return false;
foreach($GLOBALS['config']['site']['npc'] as $word)
if($word == $name_to_check)
return false;
for($i = 0; $i < strlen($name_to_check); $i++)
if($name_to_check[$i-1] == ' ' && $name_to_check[$i+1] == ' ')
return false;
foreach($words_blocked as $word)
if (!(strpos($name_to_check, $word) === false))
return false;
for($i = 0; $i < strlen($name_to_check); $i++)
if($name_to_check[$i] == $name_to_check[($i+1)] && $name_to_check[$i] == $name_to_check[($i+2)])
return false;
for($i = 0; $i < strlen($name_to_check); $i++)
if($name_to_check[$i-1] == ' ' && $name_to_check[$i+1] == ' ')
return false;
$temp = strspn("$name", "qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM- '");
if ($temp != strlen($name))
return false;
else
{
$ok = "/[a-zA-Z ']{1,25}/";
return (preg_match($ok, $name))? true: false;
}
}

//is rank name valid?
function check_rank_name($name)//sprawdza name
{
$temp = strspn("$name", "qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM0123456789-[ ] ");
if ($temp != strlen($name)) {
return false;
}
else
{
$ok = "/[a-zA-Z ]{1,60}/";
return (preg_match($ok, $name))? true: false;
}
}
//is guild name valid?
function check_guild_name($name)
{
$temp = strspn("$name", "qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM0123456789- ");
if ($temp != strlen($name)) {
return false;
}
else
{
$ok = "/[a-zA-Z ]{1,60}/";
return (preg_match($ok, $name))? true: false;
}
}
//is it valid password?
function check_password($pass)//sprawdza haslo
{
$temp = strspn("$pass", "qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM1234567890");
if ($temp != strlen($pass)) {
return false;
}
else
{
$ok = "/[a-zA-Z0-9]{1,40}/";
return (preg_match($ok, $pass))? true: false;
}
}
//is it valid e-mail?
function check_mail($email)//sprawdza mail
{
$ok = "/[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z]{2,4}/";
return (preg_match($ok, $email))? true: false;
}

function items_on_player($characterid, $pid)
{
global $SQL;
$item = $SQL->query("SELECT `itemtype` FROM `player_items` WHERE `pid` = '".$pid."' AND `player_id` = '".$characterid."'")->fetch();
return '<img src="images/items/' . $item['itemtype'] . '.gif" />';
}

function showCommentsCount( $date )
{
global $SQL;
$result = $SQL->query("SELECT COUNT(*) as `count` FROM `z_news_comments` WHERE `news_date` = " . (int)$date . ";")->fetch();
return (int)$result['count'];
}

function getReason($reasonId)
{
switch($reasonId)
{
case 0:
return "Offensive Name";
case 1:
return "Invalid Name Format";
case 2:
return "Unsuitable Name";
case 3:
return "Name Inciting Rule Violation";
case 4:
return "Offensive Statement";
case 5:
return "Spamming";
case 6:
return "Illegal Advertising";
case 7:
return "Off-Topic Public Statement";
case 8:
return "Non-English Public Statement";
case 9:
return "Inciting Rule Violation";
case 10:
return "Bug Abuse";
case 11:
return "Game Weakness Abuse";
case 12:
return "Using Unofficial Software to Play";
case 13:
return "Hacking";
case 14:
return "Multi-Clienting";
case 15:
return "Account Trading or Sharing";
case 16:
return "Threatening Gamemaster";
case 17:
return "Pretending to Have Influence on Rule Enforcement";
case 18:
return "False Report to Gamemaster";
case 19:
return "Destructive Behaviour";
case 20:
return "Excessive Unjustified Player Killing";
case 21:
return "Invalid Payment";
case 22:
return "Spoiling Auction";
default:
break;
}
return "Unknown Reason";
}

//################### DISPLAY FUNCTIONS #####################
//return shorter text (news ticker)
function short_text($text, $chars_limit)
{
if (strlen($text) > $chars_limit)
return substr($text, 0, strrpos(substr($text, 0, $chars_limit), " ")).'...';
else return $text;
}
//return text to news msg
function news_place() {
if($GLOBALS['subtopic'] == "latestnews") {
//add tickers to site - without it tickers will not be showed
//$news .= $GLOBALS['news_content'];
/*
//featured article
$layout_name = $GLOBALS['layout_name'];
$news .= ' <div id="featuredarticle" class="Box">
<div class="Corner-tl" style="background-image:url('.$layout_name.'/images/content/corner-tl.gif);"></div>
<div class="Corner-tr" style="background-image:url('.$layout_name.'/images/content/corner-tr.gif);"></div>
<div class="Border_1" style="background-image:url('.$layout_name.'/images/content/border-1.gif);"></div>
<div class="BorderTitleText" style="background-image:url('.$layout_name.'/images/content/title-background-green.gif);"></div>
<img class="Title" src="'.$layout_name.'/images/strings/headline-featuredarticle.gif" alt="Contentbox headline" />
<div class="Border_2">
<div class="Border_3">
<div class="BoxContent" style="background-image:url('.$layout_name.'/images/content/scroll.gif);">
<div id=\'TeaserThumbnail\'><img src="'.$layout_name.'/images/news/features.jpg" width=150 height=100 border=0 alt="" /></div><div id=\'TeaserText\'><div style="position: relative; top: -2px; margin-bottom: 2px;" >
<b>Tutaj wpisz tytul</b></div>
tutaj wpisz tresc newsa<br>
zdjecie laduje sie w <i>tibiacom/images/news/features.jpg</i><br>
skad sie laduje mozesz zmienic linijke ponad komentarzem
</div> </div>
</div>
</div>
<div class="Border_1" style="background-image:url('.$layout_name.'/images/content/border-1.gif);"></div>
<div class="CornerWrapper-b"><div class="Corner-bl" style="background-image:url('.$layout_name.'/images/content/corner-bl.gif);"></div></div>
<div class="CornerWrapper-b"><div class="Corner-br" style="background-image:url('.$layout_name.'/images/content/corner-br.gif);"></div></div>
</div>';
*/
}
return $news;
}
//set monster of week
function logo_monster() {
return str_replace(" ", "", trim(mb_strtolower($GLOBALS['layout_ini']['logo_monster'])));
}
$statustimeout = 1;
foreach(explode("*", str_replace(" ", "", $config['server']['statusTimeout'])) as $status_var)
if($status_var > 0)
$statustimeout = $statustimeout * $status_var;
$statustimeout = $statustimeout / 1000;
$config['status'] = parse_ini_file('config/serverstatus');
if($config['status']['serverStatus_lastCheck']+$statustimeout < time())
{
$config['status']['serverStatus_checkInterval'] = $statustimeout+3;
$config['status']['serverStatus_lastCheck'] = time();
$info = chr(6).chr(0).chr(255).chr(255).'info';
$sock = @fsockopen($config['server']['ip'], $config['server']['statusPort'], $errno, $errstr, 1);
if ($sock)
{
fwrite($sock, $info);
$data='';
while (!feof($sock))
$data .= fgets($sock, 1024);
fclose($sock);
preg_match('/players online="(\d+)" max="(\d+)"/', $data, $matches);
$config['status']['serverStatus_online'] = 1;
$config['status']['serverStatus_players'] = $matches[1];
$config['status']['serverStatus_playersMax'] = $matches[2];
preg_match('/uptime="(\d+)"/', $data, $matches);
$h = floor($matches[1] / 3600);
$m = floor(($matches[1] - $h*3600) / 60);
$config['status']['serverStatus_uptime'] = $h.'h '.$m.'m';
preg_match('/monsters total="(\d+)"/', $data, $matches);
$config['status']['serverStatus_monsters'] = $matches[1];
}
else
{
$config['status']['serverStatus_online'] = 0;
$config['status']['serverStatus_players'] = 0;
$config['status']['serverStatus_playersMax'] = 0;
}
$file = fopen("config/serverstatus", "w");
foreach($config['status'] as $param => $data)
{
$file_data .= $param.' = "'.str_replace('"', '', $data).'"
';
}
rewind($file);
fwrite($file, $file_data);
fclose($file);
}

//PAGE VIEWS COUNTER :)
$views_counter = "usercounter.dat";
// checking if the file exists
if (file_exists($views_counter)) {
// het bestand bestaat, waarde + 1
$actie = fopen($views_counter, "r+");
$page_views = fgets($actie, 9);
$page_views++;
rewind($actie);
fputs($actie, $page_views, 9);
fclose($actie);
}
else
{
// the file doesn't exist, creating a new one with value 1
$actie = fopen($views_counter, "w");
$page_views = 1;
fputs($actie, $page_views, 9);
fclose($actie);
}

function makeOrder($arr, $order, $default) {
// Function by Colandus!
$type = 'asc';
if(isset($_GET['order'])) {
$v = explode('_', strrev($_GET['order']), 2);
if(count($v) == 2)
if($orderBy = $arr[strrev($v[1])])
$default = $orderBy;
$type = (strrev($v[0]) == 'asc' ? 'desc' : 'asc');
}

return 'ORDER BY ' . $default . ' ' . $type;
}

function getOrder($arr, $order, $this) {
// Function by Colandus!
$type = 'asc';
if($orderBy = $arr[$this])
if(isset($_GET[$order])) {
$v = explode('_', strrev($_GET[$order]), 2);
if(strrev($v[1]) == $this)
$type = (strrev($v[0]) == 'asc' ? 'desc' : 'asc');
}

return $this . '_' . $type;
}

// Parse smiley bbcode into HTML images
function parsesmileys($message)
{
	foreach(array(
		"/\:\)/si" => "<img src='images/smiley/smile.gif' title='Smile'>",
		"/\;\)/si" => "<img src='images/smiley/wink.gif' title='Wink'>",
		"/\:\(/si" => "<img src='images/smiley/sad.gif' title='Sad'>",
		"/\:\|/si" => "<img src='images/smiley/frown.gif' title='Frown'>",
		"/\:o/si" => "<img src='images/smiley/shock.gif' title='Shock'>",
		"/\:p/si" => "<img src='images/smiley/pfft.gif' title='Pfft!'>",
		"/b\)/si" => "<img src='images/smiley/cool.gif' title='Cool...'>",
		"/\:d/si" => "<img src='images/smiley/grin.gif' title='Grin'>",
		"/\:@/si" => "<img src='images/smiley/angry.gif' title='Angry'>",
		"/\:rol:/si" => "<img title='Rolleyes...' src='images/smiley/roll.gif'>",
		"/\:uhoh:/si" => "<img title='Uh-Oh!' src='images/smiley/uhoh.gif'>",
		"/\:no:/si" => "<img title='Nope' src='images/smiley/no.gif'>",
		"/\:shy:/si" => "<img title='Shy' src='images/smiley/shy.gif'>",
		"/\:lol:/si" => "<img title='Laugh' src='images/smiley/laugh.gif'>",
		"/\:rip:/si" => "<img title='Dead...' src='images/smiley/dead.gif'>",
		"/\:yes:/si" => "<img title='Yeah' src='images/smiley/yes.gif'>",
		"/\:mad:/si" => "<img title='Mad' src='images/smiley/mad.gif'>",
		"/\:bigeek:/si" => "<img title='Big eek!' src='images/smiley/bigeek.gif'>",
		"/\:bigrazz:/si" => "<img title='Big razz' src='images/smiley/bigrazz.gif'>",
		"/\:smilewinkgrin:/si" => "<img title='Smile-Wink-Grin' src='images/smiley/smilewinkgrin.gif'>",
		"/\:sourgrapes:/si" => "<img title='Sour Grapes' src='images/smiley/sourgrapes.gif'>",
		"/\:confused:/si" => "<img title='Confused?' src='images/smiley/confused.gif'>",
		"/\:upset:/si" => "<img title='Upset' src='images/smiley/upset.gif'>",
		"/\:sleep:/si" => "<img title='Sleep' src='images/smiley/sleep.gif'>",
		"/\:yupi:/si" => "<img title='Yupi!' src='images/smiley/jupi.gif'>"
	) as $key => $img)
		$message = preg_replace($key, $img, $message);

	return $message;
}

// Parse bbcode into HTML code
function parseubb($text)
{
	global $account_logged;
	$text = preg_replace('#\[b\](.*?)\[/b\]#si', '<b>\1</b>', $text);

	$text = preg_replace('#\[i\](.*?)\[/i\]#si', '<i>\1</i>', $text);
	$text = preg_replace('#\[u\](.*?)\[/u\]#si', '<u>\1</u>', $text);
	$text = preg_replace('#\[s\](.*?)\[/s\]#si', '<s>\1</s>', $text);
	$text = preg_replace('#\[center\](.*?)\[/center\]#si', '<center>\1</center>', $text);

	$text = preg_replace('#\[url\]([\r\n\s]*)(http://|ftp://|https://|ftps://)([^\s\'\"\+\(\)]*?)([\r\n\s]*)\[/url\]#sie', "'<a href=\''.str_replace('<br>', '', '\\2\\3').'\' target=\'_blank\' title=\''.str_replace('<br>', '', '\\2\\3').'\'>\\2\\3</a>'", $text);
	$text = preg_replace('#\[url\] ([\r\n]*)([^\s\'\"\+\(\)]*?)([\r\n]*) \[/url\]#sie', "'<a href=\'http://'.str_replace('<br>', '', '\\2').'\' target=\'_blank\' title=\''.str_replace('<br>', '', '\\2').'\'>\\2</a>'", $text);
	$text = preg_replace('#\[url=([\r\n]*)(http://|ftp://|https://|ftps://)([^\s\'\"\+\(\)]*?)\](.*?)([\r\n]*)\[/url\]#sie', "'<a href=\''.str_replace('<br>', '', '\\2\\3').'\' target=\'_blank\' title=\''.str_replace('<br>', '', '\\2\\3').'\'>\\4</a>'", $text);
	$text = preg_replace('#\[url=([\r\n]*)([^\s\'\"\+\(\)]*?)\](.*?)([\r\n]*)\[/url\]#sie', "'<a href=\'http://'.str_replace('<br>', '', '\\2').'\' target=\'_blank\' title=\''.str_replace('<br>', '', '\\2').'\'>\\3</a>'", $text);

	$text = preg_replace('#\[mail\]([\r\n]*)([^\s\'\";:\+]*?)([\r\n]*)\[/mail\]#si', '<a href=\'mailto:\2\'>\2</a>', $text);
	$text = preg_replace('#\[mail=([\r\n]*)([^\s\'\";:\+]*?)\](.*?)([\r\n]*)\[/mail\]#si', '<a href=\'mailto:\2\'>\2</a>', $text);

	$text = preg_replace('#\[small\](.*?)\[/small\]#si', '<small>\1</small>', $text);
	$text = preg_replace('#\[color=(black|blue|brown|cyan|gray|green|lime|maroon|navy|olive|orange|purple|red|silver|violet|white|yellow)\](.*?)\[/color\]#si', '<span style=\'color:\1\'>\2</span>', $text);

	if($account_logged)
		$text = preg_replace('#\[hide\](.*?)\[/hide\]#si', '\1', $text);

	$text = preg_replace('#\[size=(8|10|12|14|16|18|20)\](.*?)\[/size\]#si', '<span style=\'font-size: \1;\'>\2</span>', $text);
	$text = preg_replace('#\[marquee\](.*?)\[/marquee\]#si', '<marquee>\1</marquee>', $text);
	$text = preg_replace('#\[marquee=(left|down|up|right)\](.*?)\[/marquee\]#si', '<marquee direction=\'\1\'>\2</marquee>', $text);
	$text = preg_replace('#\[marquee=(left|down|up|right):(scroll|slide|alternate)\](.*?)\[/marquee\]#si', '<marquee direction=\'\1\' behavior=\'\2\'>\3</marquee>', $text);

	$text = preg_replace('#\[flash width=([0-9]*?) height=([0-9]*?)\]([^\s\'\";:\+]*?)(\.swf)\[/flash\]#si', '<object classid=\'clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\' codebase=\'http://active.macromedia.com/flash6/cabs/swflash.cab#version=6,0,0,0\' id=\'\3\4\' width=\'\1\' height=\'\2\'><param name=movie value=\'\3\4\'><param name=\'quality\' value=\'high\'><param name=\'bgcolor\' value=\'#ffffff\'><embed src=\'\3\4\' quality=\'high\' bgcolor=\'#ffffff\' width=\'\1\' height=\'\2\' type=\'application/x-shockwave-flash\' pluginspage=\'http://www.macromedia.com/go/getflashplayer\'></embed></object>', $text);
	$text = preg_replace("#\[img\]((http|ftp|https|ftps)://)(.*?)(\.(jpg|jpeg|gif|png|JPG|JPEG|GIF|PNG))\[/img\]#sie","'<img src=\'\\1'.str_replace(array('.php','?','&','='),'','\\3').'\\4\' style=\'border:0px\' alt=\'\'>'",$text);

	$qcount = substr_count($text, "[quote]");
	for($i=0;$i<$qcount;$i++)
		$text = preg_replace('#\[quote\](.*?)\[/quote\]#si', '<div class=\'quote\'>\1</div>', $text);

	$ccount = substr_count($text, "[code]");
	for($i=0;$i<$ccount;$i++)
		$text = preg_replace('#\[code\](.*?)\[/code\]#si', '<div class=\'quote\' style=\'width:400px;white-space:nowrap;overflow:auto\'><code style=\'white-space:nowrap\'>\1<br><br><br></code></div>', $text);

	return descript($text, false);
}

function descript($text, $striptags = true)
{
	// Convert problematic ascii characters to their true values
	$search = array("40","41","58","65","66","67","68","69","70",
		"71","72","73","74","75","76","77","78","79","80","81",
		"82","83","84","85","86","87","88","89","90","97","98",
		"99","100","101","102","103","104","105","106","107",
		"108","109","110","111","112","113","114","115","116",
		"117","118","119","120","121","122"
		);
	$replace = array("(",")",":","a","b","c","d","e","f","g","h",
		"i","j","k","l","m","n","o","p","q","r","s","t","u",
		"v","w","x","y","z","a","b","c","d","e","f","g","h",
		"i","j","k","l","m","n","o","p","q","r","s","t","u",
		"v","w","x","y","z"
		);

	$entities = count($search);
	for($i=0;$i<$entities;$i++)
		$text = preg_replace("#(&\#)(0*".$search[$i]."+);*#si", $replace[$i], $text);

	// kill hexadecimal characters completely
	$text = preg_replace('#(&\#x)([0-9A-F]+);*#si', "", $text);
	// remove any attribute starting with "on" or xmlns
	$text = preg_replace('#(<[^>]+[\\"\'\s])(onmouseover|onmousedown|onmouseup|onmouseout|onmousemove|onclick|ondblclick|onload|xmlns)[^>]*>#iU', ">", $text);
	// remove javascript: and vbscript: protocol
	$text = preg_replace('#([a-z]*)=([\`\'\"]*)script:#iU', '$1=$2nojscript...', $text);
	$text = preg_replace('#([a-z]*)=([\`\'\"]*)javascript:#iU', '$1=$2nojavascript...', $text);
	$text = preg_replace('#([a-z]*)=([\'\"]*)vbscript:#iU', '$1=$2novbscript...', $text);
        //<span style="width: expression(alert('Ping!'));"></span> (only affects ie...)
	$text = preg_replace('#(<[^>]+)style=([\`\'\"]*).*expression\([^>]*>#iU', "$1>", $text);
	$text = preg_replace('#(<[^>]+)style=([\`\'\"]*).*behaviour\([^>]*>#iU', "$1>", $text);
	if(!$striptags)
		return $text;

	do
	{
		$tmp = $text;
		$text = preg_replace('#</*(applet|meta|xml|blink|link|style|script|embed|object|iframe|frame|frameset|ilayer|layer|bgsound|title|base)[^>]*>#i', "", $text);
	} while($tmp != $text);
	return $text;
}

function verifyimage($file)
{
	$txt = file_get_contents($file);
	if(preg_match("#([a-z]*)=([\`\'\"]*)script:#iU", $txt)) return false;
	if(preg_match("#([a-z]*)=([\`\'\"]*)javascript:#iU", $txt)) return false;
	if(preg_match("#([a-z]*)=([\'\"]*)vbscript:#iU", $txt)) return false;
	if(preg_match("#(<[^>]+)style=([\`\'\"]*).*expression\([^>]*>#iU", $txt)) return false;
	if(preg_match("#(<[^>]+)style=([\`\'\"]*).*behaviour\([^>]*>#iU", $txt)) return false;
	if(preg_match("#</*(applet|body|head|html|link|style|script|iframe|frame|frameset)[^>]*>#i", $txt)) return false;
	return true;
}

?>

