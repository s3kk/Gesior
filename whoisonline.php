<?PHP
$update_interval = 2;
if(count($config['site']['worlds']) > 1)
{
	$worlds .= '<i>Select world:</i> ';
	foreach($config['site']['worlds'] as $id => $world_n)
	{
		$worlds .= ' <a href="?subtopic=whoisonline&world='.$id.'">'.$world_n.'</a> , ';
		if($id == (int) $_GET['world'])
		{
			$world_id = $id;
			$world_name = $world_n;
		}
	}
	$main_content .= substr($worlds, 0, strlen($worlds)-3);
}
if(!isset($world_id))
{
	$world_id = 0;
	$world_name = $config['server']['serverName'];
}
$order = $_REQUEST['order'];
if($order == 'level')
	$orderby = 'level';
elseif($order == 'vocation')
	$orderby = 'vocation';
if(empty($orderby))
	$orderby = 'name';
$tmp_file_name = 'cache/whoisonline-'.$orderby.'-'.$world_id.'.tmp';
if(file_exists($tmp_file_name) && filemtime($tmp_file_name) > (time() - $update_interval))
{
	$tmp_file_content = explode(",", file_get_contents($tmp_file_name));
	$number_of_players_online = $tmp_file_content[0];
	$players_rows = $tmp_file_content[1];
}
else
{
	$players_online_data = $SQL->query('SELECT * FROM players WHERE world_id = '.(int) $world_id.' AND online > 0 ORDER BY '.$orderby);
	$number_of_players_online = 0;
	foreach($players_online_data as $player)
	{
		$number_of_players_online++;
                $acc = $SQL->query('SELECT flag, premdays FROM '.$SQL->tableName('accounts').' WHERE '.$SQL->fieldName('id').' = '.$player['account_id'].' LIMIT 1;')->fetch();
		if(is_int($number_of_players_online / 2))
			$bgcolor = $config['site']['darkborder'];
		else
			$bgcolor = $config['site']['lightborder'];
			 $rs = "";
if ($player['skulltime'] > 0 && $player['skull'] == 3)
                $rs = "<img style='border: 0;' src='./images/whiteskull.gif'/>";
        elseif ($player['skulltime'] =  $player['skull'] == 4)
                $rs = "<img style='border: 0;' src='./images/redskull.gif'/>";
        elseif ($player['skulltime'] =  $player['skull'] == 5)
                $rs = "<img style='border: 0;' src='./images/blackskull.gif'/>";
                
		$players_rows .= '<TR BGCOLOR='.$bgcolor.'><TD WIDTH=5%><center><image src="images/flags/'.$acc['flag'].'.png"/><TD><div style="position: relative; width: 32px; height: 32px;"><div style="background-image: url(\'outfitter/outfitter.php?id='.$player['looktype'].'&addons='.$player['lookaddons'].'&head='.$player['lookhead'].'&body='.$player['lookbody'].'&legs='.$player['looklegs'].'&feet='.$player['lookfeet'].'\'); position: absolute; width: 64px; height: 80px; background-position: bottom right; background-repeat: no-repeat; right: 0px; bottom: 0px;"></div></div></TD></center></TD><TD WIDTH=60%><A HREF="?subtopic=characters&name='.$player['name'].'">'.$player['name'].$rs.'</A></TD>
		<TD WIDTH=10%>'.$player['level'].'</TD><TD WIDTH=20%>'.$vocation_name[$world_id][$player['promotion']][$player['vocation']].'</TD><TD width="10%">'.($acc['premdays'] > 0 ? '<font color="green"><b>YES</b></font>' : '<font color="red"><b>NO</b></font></TD>').'</TR>';
	}
	file_put_contents($tmp_file_name, $number_of_players_online.','.$players_rows);
}
//Wykresik

if($number_of_players_online == 0)
	//server status - server empty
	$main_content .= '<TABLE BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%><TR BGCOLOR="'.$config['site']['vdarkborder'].'"><TD CLASS=white><B>Server Status</B></TD></TR><TR BGCOLOR='.$config['site']['darkborder'].'><TD><TABLE BORDER=0 CELLSPACING=1 CELLPADDING=1><TR><TD>Currently no one is playing on <b>'.$config['site']['worlds'][$world_id].'</b>.</TD></TR></TABLE></TD></TR></TABLE><BR>';
else
{
//Vocations pics
$vocs = array();
foreach($SQL->query('SELECT `vocation`, COUNT(`id`) AS `count` FROM `players` WHERE `world_id` = "'.$world_id.'" AND `online` > 0 GROUP BY `vocation`') as $entry)
	  $vocs[$entry['vocation']] = $entry['count'];






$main_content .= '



<div class="TableContainer" > 
<table class="Table1" cellpadding="0" cellspacing="0" >
<div class="CaptionContainer" >
<div class="CaptionInnerContainer" >
<span class="CaptionEdgeLeftTop" style="background-image:url(http://static.tibia.com/images/global/content/box-frame-edge.gif);" /></span>
<span class="CaptionEdgeRightTop" style="background-image:url(http://static.tibia.com/images/global/content/box-frame-edge.gif);" /></span>
<span class="CaptionBorderTop" style="background-image:url(http://static.tibia.com/images/global/content/table-headline-border.gif);" ></span>
<span class="CaptionVerticalLeft" style="background-image:url(http://static.tibia.com/images/global/content/box-frame-vertical.gif);" /></span>
<div class="Text" >World Information</div>
<span class="CaptionVerticalRight" style="background-image:url(http://static.tibia.com/images/global/content/box-frame-vertical.gif);" /></span>
<span class="CaptionBorderBottom" style="background-image:url(http://static.tibia.com/images/global/content/table-headline-border.gif);" ></span>
<span class="CaptionEdgeLeftBottom" style="background-image:url(http://static.tibia.com/images/global/content/box-frame-edge.gif);" /></span>
<span class="CaptionEdgeRightBottom" style="background-image:url(http://static.tibia.com/images/global/content/box-frame-edge.gif);" /></span>
</div></div><tr><td>
<div class="InnerTableContainer" >
<table style="width:100%;" ><tr><td class="LabelV150" ><b>Status:</b></td><td>Online</td></tr><tr><td class="LabelV150" ><b>Players Online:</b></td><td>'.$number_of_players_online.'</td></tr><tr><td class="LabelV150" ><b>Online Record:</b></td><td>'.$wynik['record'].'</td></tr><tr><td class="LabelV150" ><b>Creation Date:</b></td><td>01/01/2016</td></tr>
<tr><td class="LabelV150" ><b>Location:</b></td><td>Brazil</td></tr><tr><td class="LabelV150" ><b>PvP Type:</b></td><td>Open PvP</td></tr>
</table></div></table></div></td></tr>

<br>';
	

	
      //list of players
    $main_content .= '<TABLE BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%><TR BGCOLOR="'.$config['site']['vdarkborder'].'"><TD><a href="" CLASS=white >Country</a></TD><TD CLASS=white><b>Outfit</b></TD><TD><A HREF="?subtopic=whoisonline&order=name&world='.$world_id.'" CLASS=white>Name</A></TD><TD><A HREF="?subtopic=whoisonline&order=level&world='.$world_id.'" CLASS=white>Level</A></TD><TD><A HREF="?subtopic=whoisonline&order=vocation&world='.$world_id.'" CLASS=white>Vocation</TD><TD><a href="" CLASS=white >Premium</a></TD></TR>'.$players_rows.'</TABLE>';
	//search bar
	$main_content .= '<BR><FORM ACTION="?subtopic=characters" METHOD=post>  <TABLE WIDTH=100% BORDER=0 CELLSPACING=1 CELLPADDING=4><TR><TD BGCOLOR="'.$config['site']['vdarkborder'].'" CLASS=white><B>Search Character</B></TD></TR><TR><TD BGCOLOR="'.$config['site']['darkborder'].'"><TABLE BORDER=0 CELLPADDING=1><TR><TD>Name:</TD><TD><INPUT NAME="name" VALUE=""SIZE=29 MAXLENGTH=29></TD><TD><INPUT TYPE=image NAME="Submit" SRC="'.$layout_name.'/images/buttons/sbutton_submit.gif" BORDER=0 WIDTH=120 HEIGHT=18></TD></TR></TABLE></TD></TR></TABLE></FORM>';
}
?>
