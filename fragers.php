<?php
$main_content .= '<div style="text-align: center; font-weight: bold;">Top 30 Frags on ' . $config['server']['serverName'] . '</div>
<table border="0" cellspacing="1" cellpadding="4" width="100%">
	<tr bgcolor="' . $config['site']['vdarkborder'] . '">
		<td class="white" style="text-align: center; font-weight: bold;">Name</td>
		<td class="white" style="text-align: center; font-weight: bold;">Frags</td>
	</tr>';

$i = 0;
foreach($SQL->query('SELECT `p`.`name` AS `name`, COUNT(`p`.`name`) as `frags`
	FROM `killers` k
	LEFT JOIN `player_killers` pk ON `k`.`id` = `pk`.`kill_id`
	LEFT JOIN `players` p ON `pk`.`player_id` = `p`.`id`
WHERE `k`.`unjustified` = 1
	GROUP BY `name`
	ORDER BY `frags` DESC, `name` ASC
	LIMIT 0,30;') as $player)
{
	$i++;
	$main_content .= '<tr bgcolor="' . (is_int($i / 2) ? $config['site']['lightborder'] : $config['site']['darkborder']) . '">
		<td><a href="?subtopic=characters&name=' . urlencode($player['name']) . '">' . $player['name'] . '</a></td>
		<td style="text-align: center;">' . $player['frags'] . '</td>
	</tr>';
}

$main_content .= '</table>';
?>

