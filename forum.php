<?PHP
$main_content .= '<style type="text/css">
<!-- .Style1 {color: black}
       .Header {color: black;font-size: small}
       .SubHeader {color: white;font-size: x-small}
       .Short {font-size: x-small}
       .Style2 {color: white} -->
</style>';

/*
CREATE TABLE `z_forum` (
  `id` int(11) NOT NULL auto_increment,
  `sticky` tinyint(1) NOT NULL default '0',
  `closed` tinyint(1) NOT NULL default '0',
  `first_post` int(11) NOT NULL default '0',
  `last_post` int(11) NOT NULL default '0',
  `section` int(3) NOT NULL default '0',
  `replies` int(20) NOT NULL default '0',
  `views` int(20) NOT NULL default '0',
  `author_aid` int(20) NOT NULL default '0',
  `author_guid` int(20) NOT NULL default '0',
  `post_text` text NOT NULL,
  `post_topic` varchar(255) NOT NULL,
  `post_smile` tinyint(1) NOT NULL default '0',
  `post_date` int(20) NOT NULL default '0',
  `last_edit_aid` int(20) NOT NULL default '0',
  `edit_date` int(20) NOT NULL default '0',
  `post_ip` varchar(32) NOT NULL default '0.0.0.0',
  PRIMARY KEY  (`id`),
  KEY `section` (`section`)
) ENGINE=MyISAM AUTO_INCREMENT=1 ;
*/
// CONFIG
$level_limit = 30; // minimum 1 character with 50 lvl on account to post
$post_interval = 20; // 30 seconds between posts
$group_not_blocked = 1; // group id of player that can always post, remove post, remove threads
$posts_per_page = 20;
$threads_per_page = 20;
      $sections = array(1 => 'Novidades', 2 => '<b>Reclama&ccedil;&otilde;es ou d&uacute;vidas</b>', 3 => 'Tutoriais', 4 => 'Imagens', 5 => 'Bug Report', 6 => 'Geral', 7 => 'Trade');
$sections_desc = array(1 => 'Veja as novidades.', 2 => 'Poste suas reclama&ccedil;&otilde;es ou d&uacute;vidas aqui.', 3 => 'Poste seus tutorias.', 4 => 'Poste suas imagens aqui', 5 => 'Report os bugs para melhoramos cada vez mais.', 6 => 'Assuntos aleat&oacute;rios.', 7 => 'Compra e venda de items.');
// END
function canPost($account)
{
    if($account->isLoaded())
        if(!$account->isBanned())
        {
            $SQL = $GLOBALS['SQL'];
            $level_limit = $GLOBALS['level_limit'];
            $player = $SQL->query("SELECT `level` FROM `players` WHERE `account_id` = ".$SQL->quote($account->getId())." ORDER BY `level` DESC")->fetch();
            if($player['level'] >= $level_limit)
                return true;
        }
    return false;
}

function replaceSmile($text, $smile)
{
    $smileys = array(';D' => 1, ':D' => 1, ':cool:' => 2, ';cool;' => 2, ':ekk:' => 3, ';ekk;' => 3, ';o' => 4, ';O' => 4, ':o' => 4, ':O' => 4, ':(' => 5, ';(' => 5, ':mad:' => 6, ';mad;' => 6, ';rolleyes;' => 7, ':rolleyes:' => 7, ':)' => 8, ';d' => 9, ':d' => 9, ';)' => 10);
    if($smile == 1)
        return $text;
    else
    {
        foreach($smileys as $search => $replace)
            $text = str_replace($search, '<img src="images/smile/'.$replace.'.gif" />', $text);
        return $text;
    }
}

function replaceAll($text, $smile)
{
    $rows = 0;
    while(stripos($text, '[code]') !== false && stripos($text, '[/code]') !== false )
    {
        $code = substr($text, stripos($text, '[code]')+6, stripos($text, '[/code]') - stripos($text, '[code]') - 6);
        if(!is_int($rows / 2)) { $bgcolor = 'ABED25'; } else { $bgcolor = '23ED25'; } $rows++;
        $text = str_ireplace('[code]'.$code.'[/code]', '<i>Code:</i><br /><table cellpadding="0" style="background-color: #'.$bgcolor.'; width: 480px; border-style: dotted; border-color: #CCCCCC; border-width: 2px"><tr><td>'.$code.'</td></tr></table>', $text);
    }
    $rows = 0;
    while(stripos($text, '[quote]') !== false && stripos($text, '[/quote]') !== false )
    {
        $quote = substr($text, stripos($text, '[quote]')+7, stripos($text, '[/quote]') - stripos($text, '[quote]') - 7);
        if(!is_int($rows / 2)) { $bgcolor = 'AAAAAA'; } else { $bgcolor = 'CCCCCC'; } $rows++;
        $text = str_ireplace('[quote]'.$quote.'[/quote]', '<table cellpadding="0" style="background-color: #'.$bgcolor.'; width: 480px; border-style: dotted; border-color: #007900; border-width: 2px"><tr><td>'.$quote.'</td></tr></table>', $text);
    }
    $rows = 0;
    while(stripos($text, '[url]') !== false && stripos($text, '[/url]') !== false )
    {
        $url = substr($text, stripos($text, '[url]')+5, stripos($text, '[/url]') - stripos($text, '[url]') - 5);
        $text = str_ireplace('[url]'.$url.'[/url]', '<a href="'.$url.'" target="_blank">'.$url.'</a>', $text);
    }
    while(stripos($text, '[player]') !== false && stripos($text, '[/player]') !== false )
    {
        $player = substr($text, stripos($text, '[player]')+8, stripos($text, '[/player]') - stripos($text, '[player]') - 8);
        $text = str_ireplace('[player]'.$player.'[/player]', '<a href="?subtopic=characters&name='.urlencode($player).'">'.$player.'</a>', $text);
    }
    while(stripos($text, '[img]') !== false && stripos($text, '[/img]') !== false )
    {
        $img = substr($text, stripos($text, '[img]')+5, stripos($text, '[/img]') - stripos($text, '[img]') - 5);
        $text = str_ireplace('[img]'.$img.'[/img]', '<img src="'.$img.'">', $text);
    }
    while(stripos($text, '[b]') !== false && stripos($text, '[/b]') !== false )
    {
        $b = substr($text, stripos($text, '[b]')+3, stripos($text, '[/b]') - stripos($text, '[b]') - 3);
        $text = str_ireplace('[b]'.$b.'[/b]', '<b>'.$b.'</b>', $text);
    }
    while(stripos($text, '[i]') !== false && stripos($text, '[/i]') !== false )
    {
        $i = substr($text, stripos($text, '[i]')+3, stripos($text, '[/i]') - stripos($text, '[i]') - 3);
        $text = str_ireplace('[i]'.$i.'[/i]', '<i>'.$i.'</i>', $text);
    }
    while(stripos($text, '[u]') !== false && stripos($text, '[/u]') !== false )
    {
        $u = substr($text, stripos($text, '[u]')+3, stripos($text, '[/u]') - stripos($text, '[u]') - 3);
        $text = str_ireplace('[u]'.$u.'[/u]', '<u>'.$u.'</u>', $text);
    }
    return replaceSmile($text, $smile);
}
function codeLower($text)
{
    return str_ireplace(array('[b]', '[i]', '[u]', '[/u][/i][/b][i][u]', '[/u][/i][u]', '[/u]', '[url]', '[player]', '[img]', '[code]', '[quote]', '[/quote][/code][/url][code][quote]', '[/player]', '[/img]', '[/quote][/code][quote]', '[/quote]'), array('[b]', '[i]', '[u]', '[/u][/i][/b][i][u]', '[/u][/i][u]', '[/u]', '[url]', '[player]', '[img]', '[code]', '[quote]', '[/quote][/code][/url][code][quote]', '[/player]', '[/img]', '[/quote][/code][quote]', '[/quote]'), $text);
}

function isThreadOpen($thread)
{
            $SQL = $GLOBALS['SQL'];
$closed = $SQL->query( 'SELECT `closed` FROM `z_forum` WHERE `id` = '.$thread.';' )->fetch( );
        if($closed['closed'] == 0)
        {
        return true;
        }
    return false;
}

function showPost($topic, $text, $smile)
{
    $text = nl2br($text);
    $post = '';
    if(!empty($topic))
        $post .= '<b>'.replaceSmile($topic, $smile).'</b><hr />';
    $post .= replaceAll($text, $smile);
    return $post;
}
if(!$logged)
    $main_content .=  'You are not logged in. <a href="?subtopic=accountmanagement">Log in</a> to post on the forum.<br /><br />';

if($action == '')
{
    $main_content .= '<b>Boards</b><br />';
     $main_content .= '<a STYLE="text-decoration: none" href="?subtopic=forum&action=recent_posts"><b>View Recent Posts</b></a>';
    $main_content .= '<table width="100%"><tr bgcolor="'.$config['site']['vdarkborder'].'"><td><font color="white" size="1"><b>Board</b></font></td><td><font color="white" size="1"><b>Posts</b></font></td><td><font color="white" size="1"><b>Threads</b></font></td><td align="center"><font color="white" size="1"><b>Last Post</b></font></td></tr>';
    $info = $SQL->query("SELECT `section`, COUNT(`id`) AS 'threads', SUM(`replies`) AS 'replies' FROM `z_forum` WHERE `first_post` = `id` GROUP BY `section`")->fetchAll();
    foreach($info as $data)
        $counters[$data['section']] = array('threads' => $data['threads'], 'posts' => $data['replies'] + $data['threads']);
    foreach($sections as $id => $section)
    {
        $last_post = $SQL->query("SELECT `players`.`name`, `z_forum`.`post_date` FROM `players`, `z_forum` WHERE `z_forum`.`section` = ".(int) $id." AND `players`.`id` = `z_forum`.`author_guid` ORDER BY `post_date` DESC LIMIT 1")->fetch();
        if(!is_int($number_of_rows / 2)) { $bgcolor = $config['site']['darkborder']; } else { $bgcolor = $config['site']['lightborder']; } $number_of_rows++;
        $main_content .= '<tr bgcolor="'.$bgcolor.'"><td><a href="?subtopic=forum&action=show_board&id='.$id.'">'.$section.'</a><br /><small>'.$sections_desc[$id].'</small></td><td>'.(int) $counters[$id]['posts'].'</td><td>'.(int) $counters[$id]['threads'].'</td><td>';
        if(isset($last_post['name']))
            $main_content .= date('d.m.y H:i:s', $last_post['post_date']).'<br />by <a href="?subtopic=characters&name='.urlencode($last_post['name']).'">'.$last_post['name'].'</a>';
        else
            $main_content .= 'No posts';
        $main_content .= '</td></tr>';

    }
    $main_content .= '</table>';
}
####################################################
//Latest Posts function for Gesior Forum. Scripted by Cybermaster
if($action == 'recent_posts')
{
    $main_content .= '<a href="?subtopic=forum">Forum</a> &gt;&gt; <b><b>Latest posts</b><br/>';
    $main_content .= '<table width="100%" border="0" cellpadding="0" cellspacing="1"><tr bgcolor="'.$config['site']['vdarkborder'].'" align="left"><td><font size="1"><span class="SubHeader"><strong>Thread</strong></td><td align="center"><span class="SubHeader"><strong>Last Post</strong></span></td><td align="center"><span class="SubHeader"><strong>Replies</b></span></td><td align="center"><span class="SubHeader"><strong>Views</strong></span></td><td align="left"><span class="SubHeader"><strong>Forum</strong></span></td></tr>';

$order = 0;
$posts = $SQL->query('SELECT id,first_post,post_topic,post_date,author_guid,post_text,section FROM z_forum ORDER BY post_date DESC LIMIT 40;');

foreach($posts as $post) {
$order++;
if(is_int($order / 2))
            $bgcolor = $config['site']['darkborder'];
        else
            $bgcolor = $config['site']['lightborder'];

$_name = $SQL->query('SELECT `name` FROM `players` WHERE `id` = '.$post['author_guid'].'')->fetch();
$_replies = $SQL->query('SELECT `replies` FROM `z_forum` WHERE `first_post` = '.$post['first_post'].'')->fetch();
$_views = $SQL->query('SELECT `views` FROM `z_forum` WHERE `first_post` = '.$post['first_post'].'')->fetch();
$_text = $post['post_text'];
$_thread = $SQL->query('SELECT `post_topic` FROM `z_forum` WHERE `first_post` = '.$post['first_post'].'')->fetch();
if (strlen($_thread[0]) > 50) //Char Limit Config for the Threads
    $_thread[0] = substr($_thread[0], 0, strrpos(substr($_thread[0], 0, 50), ' ')) . '...';
if (strlen($_text) > 50) //Char Limit Config for the Posts
    $_text = substr($_text, 0, strrpos(substr($_text, 0, 50), ' ')) . '...';

$main_content .= '<tr bgcolor="'.$bgcolor.'">
<td><img src="http://otland.net/images/galaxy/misc/multipage.gif"> "'.$_text.'"</span><br/><img src="http://otland.net/images/galaxy/buttons/firstnew.gif"><b><a href="?subtopic=forum&action=show_thread&id='.urlencode($post['first_post']).'"><font size="1"><span class="Style1"> '.$_thread[0].'</a></b></span></td>
<td style="text-align: right;"><font size="1"><span class="Style1">'.date("F d (H:m)", $post[post_date]).' <br/>by <strong>'.$_name[0].' </strong></span><a href="?subtopic=forum&action=show_thread&id='.urlencode($post['first_post']).'"><img src="http://otland.net/images/galaxy/buttons/lastpost.gif" style="border: none;"></a></td>
<td><center><span class="Style1">'.$_replies[0].'</center></span></td>
<td><center><span class="Style1">'.$_views[0].'</center></span></td>
<td><span class="Short">'.$sections[$post[section]].'</span></td></tr>';
    }
$main_content .= '</table>';
}
####################################################>
if($action == 'show_board')

{
    $section_id = (int) $_REQUEST['id'];
    $page = (int) $_REQUEST['page'];
    $threads_count = $SQL->query("SELECT COUNT(`z_forum`.`id`) AS threads_count FROM `players`, `z_forum` WHERE `players`.`id` = `z_forum`.`author_guid` AND `z_forum`.`section` = ".(int) $section_id." AND `z_forum`.`first_post` = `z_forum`.`id`")->fetch();
    for($i = 0; $i < $threads_count['threads_count'] / $threads_per_page; $i++)
    {
        if($i != $page)
            $links_to_pages .= '<a href="?subtopic=forum&action=show_board&id='.$section_id.'&page='.$i.'">'.($i + 1).'</a> ';
        else
            $links_to_pages .= '<b>'.($i + 1).' </b>';
    }
    $main_content .= '<a href="?subtopic=forum">Boards</a> >> <b>'.$sections[$section_id].'</b><br /><br /><a href="?subtopic=forum&action=new_topic&section_id='.$section_id.'"><img src="images/topic.gif" border="0" /></a><br /><br />Page: '.$links_to_pages.'<br />';
    $last_threads = $SQL->query("SELECT * FROM `players`, `z_forum` WHERE `players`.`id` = `z_forum`.`author_guid` AND `z_forum`.`section` = ".(int) $section_id." AND `z_forum`.`first_post` = `z_forum`.`id` ORDER BY `sticky` DESC, `post_date` DESC LIMIT ".$threads_per_page." OFFSET ".($page * $threads_per_page))->fetchAll();  
    if(isset($last_threads[0]))
    {
        $main_content .= '<table width="100%"><tr bgcolor="'.$config['site']['vdarkborder'].'" align="center"><td><font color="white" size="1"><b>Thread</b></font></td><td><font color="white" size="1"><b>Thread Starter</b></font></td><td><font color="white" size="1"><b>Replies</b></font></td><td><font color="white" size="1"><b>Views</b></font></td><td><font color="white" size="1"><b>Last Post</b></font></td></tr>';
        foreach($last_threads as $thread)
        {
            if(!is_int($number_of_rows / 2)) { $bgcolor = $config['site']['darkborder']; } else { $bgcolor = $config['site']['lightborder']; } $number_of_rows++;
            $main_content .= '<tr bgcolor="'.$bgcolor.'"><td>';
            $stick = ""; $stix = '<span style="color:blue">[STICK]</span>'; if ( $thread['sticky'] == 1 ) { $stick = '<strong>STICKY: </strong>'; $stix = '<span style="color:orange">[UNSTICK]</span>'; }
			$closed = '<span style="color:purple">[CLOSE]</span>'; if ( $thread['closed'] == 1 ) { $closed = '<span style="color:purple">[OPEN]</span>'; }
            if($logged && $group_id_of_acc_logged >= $group_not_blocked)
                $main_content .= '<a href="?subtopic=forum&action=close_thread&id='.$thread['id'].'")">'.$closed.'</a> <a href="?subtopic=forum&action=stick_post&id='.$thread['id'].'")">'.$stix.'</a> <a href="?subtopic=forum&action=move_thread&id='.$thread['id'].'"\')"><span style="color:darkgreen">[MOVE]</span></a> <a href="?subtopic=forum&action=remove_post&id='.$thread['id'].'" onclick="return confirm(\'Are you sure you want remove thread > '.$thread['post_topic'].' <?\')"><span style="color:red">[REMOVE]</span></a><br/>';
                $main_content .= '<b>'.$stick.'<a STYLE="text-decoration:none" href="?subtopic=forum&action=show_thread&id='.$thread['id'].'">'.htmlspecialchars($thread['post_topic']).'</a></b><br /><small>'.htmlspecialchars(substr($thread['post_text'], 0, 50)).'...</small></td><td><a href="?subtopic=characters&name='.urlencode($thread['name']).'">'.$thread['name'].'</a></td><td>'.(int) $thread['replies'].'</td><td>'.(int) $thread['views'].'</td><td>';
            if($thread['last_post'] > 0)
            {
                $last_post = $SQL->query("SELECT `players`.`name`, `z_forum`.`post_date` FROM `players`, `z_forum` WHERE `z_forum`.`first_post` = ".(int) $thread['id']." AND `players`.`id` = `z_forum`.`author_guid` ORDER BY `post_date` DESC LIMIT 1")->fetch();
                if(isset($last_post['name']))
                    $main_content .= date('d.m.y H:i:s', $last_post['post_date']).'<br />by <a href="?subtopic=characters&name='.urlencode($last_post['name']).'">'.$last_post['name'].'</a>';
                else
                    $main_content .= 'No posts.';
            }
            else
                $main_content .= date('d.m.y H:i:s', $thread['post_date']).'<br />by <a href="?subtopic=characters&name='.urlencode($thread['name']).'">'.$thread['name'].'</a>';
            $main_content .= '</td></tr>';
        }
        $main_content .= '</table><br /><a href="?subtopic=forum&action=new_topic&section_id='.$section_id.'"><img src="images/topic.gif" border="0" /></a>';
    }
    else
        $main_content .= '<h3>No threads in this board.</h3>';
}

if($action  == 'show_thread')
{
    $thread_id = (int) $_REQUEST['id'];
    $page = (int) $_REQUEST['page'];
    $thread_name = $SQL->query("SELECT `players`.`name`, `z_forum`.`post_topic` FROM `players`, `z_forum` WHERE `z_forum`.`first_post` = ".(int) $thread_id." AND `z_forum`.`id` = `z_forum`.`first_post` AND `players`.`id` = `z_forum`.`author_guid` LIMIT 1")->fetch();
    if(!empty($thread_name['name']))
    {
        $posts_count = $SQL->query("SELECT COUNT(`z_forum`.`id`) AS posts_count FROM `players`, `z_forum` WHERE `players`.`id` = `z_forum`.`author_guid` AND `z_forum`.`first_post` = ".(int) $thread_id)->fetch();
        for($i = 0; $i < $posts_count['posts_count'] / $threads_per_page; $i++)
        {
            if($i != $page)
                $links_to_pages .= '<a href="?subtopic=forum&action=show_thread&id='.$thread_id.'&page='.$i.'">'.($i + 1).'</a> ';
            else
                $links_to_pages .= '<b>'.($i + 1).' </b>';
        }
        $threads = $SQL->query("SELECT `players`.`name`, `players`.`account_id`, `players`.`world_id`, `players`.`rank_id`, `players`.`vocation`, `players`.`promotion`, `players`.`level`, `z_forum`.`id`,`z_forum`.`first_post`, `z_forum`.`section`,`z_forum`.`post_text`, `z_forum`.`post_topic`, `z_forum`.`post_date`, `z_forum`.`post_smile`, `z_forum`.`author_aid`, `z_forum`.`author_guid`, `z_forum`.`last_edit_aid`, `z_forum`.`edit_date` FROM `players`, `z_forum` WHERE `players`.`id` = `z_forum`.`author_guid` AND `z_forum`.`first_post` = ".(int) $thread_id." ORDER BY `z_forum`.`post_date` LIMIT ".$posts_per_page." OFFSET ".($page * $posts_per_page))->fetchAll();
        if(isset($threads[0]['name']))
            $SQL->query("UPDATE `z_forum` SET `views`=`views`+1 WHERE `id` = ".(int) $thread_id);
        $main_content .= '<a href="?subtopic=forum">Boards</a> >> <a href="?subtopic=forum&action=show_board&id='.$threads[0]['section'].'">'.$sections[$threads[0]['section']].'</a> >> <b>'.$thread_name['post_topic'].'</b>';

        //OPEN|CLOSE FUNCTION
        if(isThreadOpen($thread_id)) {
        $main_content .= '<br /><br /><a href="?subtopic=forum&action=new_post&thread_id='.$thread_id.'"><img src="images/post.gif" border="0" /></a><br /><br />Page: '.$links_to_pages.'<br /><table width="100%"><tr bgcolor="'.$config['site']['lightborder'].'" width="100%"><td colspan="2"><font size="4"><b>'.htmlspecialchars($thread_name['post_topic']).'</b></font><font size="1"><br />by <a href="?subtopic=characters&name='.urlencode($thread_name['name']).'">'.$thread_name['name'].'</a></font></td></tr><tr bgcolor="'.$config['site']['vdarkborder'].'"><td width="200"><font color="white" size="1"><b>Author</b></font></td><td>&nbsp;</td></tr>';}
        else
        $main_content .= '<br /><br /><strong>Thread Closed!</strong><br /><br />Page: '.$links_to_pages.'<br /><table width="100%"><tr bgcolor="'.$config['site']['lightborder'].'" width="100%"><td colspan="2"><font size="4"><b>'.htmlspecialchars($thread_name['post_topic']).'</b></font><font size="1"><br />by <a href="?subtopic=characters&name='.urlencode($thread_name['name']).'">'.$thread_name['name'].'</a></font></td></tr><tr bgcolor="'.$config['site']['vdarkborder'].'"><td width="200"><font color="white" size="1"><b>Author</b></font></td><td>&nbsp;</td></tr>';
        
        foreach($threads as $thread)
        {
            if(!is_int($number_of_rows / 2)) { $bgcolor = $config['site']['darkborder']; } else { $bgcolor = $config['site']['lightborder']; } $number_of_rows++;
            $main_content .= '<tr bgcolor="'.$bgcolor.'"><td valign="top"><a href="?subtopic=characters&name='.urlencode($thread['name']).'">'.$thread['name'].'</a><br /><br /><font size="1">Profession: '.$vocation_name[$thread['world_id']][$thread['promotion']][$thread['vocation']].'<br />Level: '.$thread['level'].'<br />';
            $rank = new OTS_GuildRank();
            $rank->load($thread['rank_id']);
            if($rank->isLoaded())
            {
                $guild = $rank->getGuild();
                if($guild->isLoaded())
                    $main_content .= $rank->getName().' of <a href="?subtopic=guilds&action=show&guild='.$guild->getId().'">'.$guild->getName().'</a><br />';
            }
            $posts = $SQL->query("SELECT COUNT(`id`) AS 'posts' FROM `z_forum` WHERE `author_aid`=".(int) $thread['account_id'])->fetch();
            $main_content .= '<br />Posts: '.(int) $posts['posts'].'<br /></font></td><td valign="top">'.showPost(htmlspecialchars($thread['?']), htmlspecialchars($thread['post_text']), $thread['post_smile']).'</td></tr>
            <tr bgcolor="'.$bgcolor.'"><td><font size="1">'.date('d.m.y H:i:s', $thread['post_date']);
            if($thread['edit_date'] > 0)
            {
                if($thread['last_edit_aid'] != $thread['author_aid'])
                    $main_content .= '<br />Edited by moderator';
                else
                    $main_content .= '<br />Edited by '.$thread['name'];
                $main_content .= '<br />on '.date('d.m.y H:i:s', $thread['edit_date']);
            }
            $main_content .= '</font></td><td>';
            if($logged && $group_id_of_acc_logged >= $group_not_blocked)
                if($thread['first_post'] != $thread['id'])
                    $main_content .= '<a href="?subtopic=forum&action=remove_post&id='.$thread['id'].'" onclick="return confirm(\'Are you sure you want remove post of '.$thread['name'].'?\')"><font color="red">REMOVE POST</font></a>';
                else
                    $main_content .= '<a href="?subtopic=forum&action=remove_post&id='.$thread['id'].'" onclick="return confirm(\'Are you sure you want remove thread > '.$thread['post_topic'].' <?\')"><font color="red">REMOVE THREAD</font></a>';
            if($logged && ($thread['account_id'] == $account_logged->getId() || $group_id_of_acc_logged >= $group_not_blocked))

            //OPEN|CLOSE FUNCTION
            if(isThreadOpen($thread_id)) {
                $main_content .= '<br/><a href="?subtopic=forum&action=edit_post&id='.$thread['id'].'">EDIT POST</a>';}
            else
                $main_content .= '';    
            if($logged)
                if(isThreadOpen($thread_id)) {
                    $main_content .= '<br/><a href="?subtopic=forum&action=new_post&thread_id='.$thread_id.'&quote='.$thread['id'].'">Quote</a>';}
            else
                $main_content .= '';
                    $main_content .= '</td></tr>';
        }
        
        //OPEN|CLOSE FUNCTION
        if(isThreadOpen($thread_id)) {
        $main_content .= '</table><br /><a href="?subtopic=forum&action=new_post&thread_id='.$thread_id.'"><img src="images/post.gif" border="0" /></a><br /><center>Pages:<br />'.$links_to_pages.'<br /></center>'; }
        else
        $main_content .= '</table><br /><strong>Thread Closed!</strong>';
            
    }
    else
        $main_content .= 'Thread with this ID does not exits.';
}

########################################
//Sticky Threads Function. Scripted by Cybermaster
if($action == 'stick_post')
{
    if($logged && $group_id_of_acc_logged >= $group_not_blocked)
    {
        $id = (int) $_REQUEST['id'];
        $post = $SQL->query("SELECT `id`, `sticky`, `first_post`, `section` FROM `z_forum` WHERE `id` = ".$id." LIMIT 1")->fetch();
        if($post['id'] == $id)
        {
            if($post['id'] == $post['first_post'])
            {
                if($post['sticky'] == 0)
                    {    
                        $SQL->query("UPDATE `z_forum` SET `sticky` = 1 WHERE `id` = ".$post['id']." ");
                        header('Location: ?subtopic=forum&action=show_board&id='.$post['section']);
                    }
                else    
                        $SQL->query("UPDATE `z_forum` SET `sticky` = 0 WHERE `id` = ".$post['id']." ");
                        header('Location: ?subtopic=forum&action=show_board&id='.$post['section']);
            }
        }
        else
            $main_content .= 'Post with ID '.$id.' does not exist.';
    }
    else
        $main_content .= 'You are not logged in or you are not moderator.';
}
########################################  

//Close Threads Function. Scripted by Cybermaster
if($action == 'close_thread')
{
	if($logged && $group_id_of_acc_logged >= $group_not_blocked)
	{
		$id = (int) $_REQUEST['id'];
		$post = $SQL->query("SELECT `id`, `closed`, `first_post`, `section` FROM `z_forum` WHERE `id` = ".$id." LIMIT 1")->fetch();
		if($post['id'] == $id)
		{
			if($post['id'] == $post['first_post'])
			{
				if($post['closed'] == 0)
					{	
						$SQL->query("UPDATE `z_forum` SET `closed` = 1 WHERE `id` = ".$post['id']." ");
						header('Location: ?subtopic=forum&action=show_board&id='.$post['section']);
					}
				else	
						$SQL->query("UPDATE `z_forum` SET `closed` = 0 WHERE `id` = ".$post['id']." ");
						header('Location: ?subtopic=forum&action=show_board&id='.$post['section']);
			}
		}
		else
			$main_content .= 'Post with ID '.$id.' does not exist.';
	}
	else
		$main_content .= 'You are not logged in or you are not moderator.';
}

//Board Change Function. Scripted by Cybermaster and Absolute Mango.
if($action == 'move_thread')
{
	if($logged && $group_id_of_acc_logged >= $group_not_blocked)
	{
		$id = (int) $_REQUEST['id'];
		$post = $SQL->query("SELECT `id`, `section`, `first_post`, `post_topic`, `author_guid` FROM `z_forum` WHERE `id` = ".$id." LIMIT 1")->fetch();
		$name= $SQL->query("SELECT `name` FROM `players` WHERE `id` = ".$post['author_guid']." ")->fetch();		
		if($post['id'] == $id)
		{
			if($post['id'] == $post['first_post'])
			{
				$main_content .= '<br/><table bgcolor='.$config['site']['vdarkborder'].' border=0 cellpadding=2 cellspacing=0 width=100%>
				<tr bgcolor='.$config['site']['vdarkborder'].'><td class=white colspan=5><B>Move thread to another board</B></td></tr>
				<tr><td><table border=0 cellpadding=3 cellspacing=1 width=100%>
				<tr bgcolor='.$config['site']['lightborder'].'><td>
				<FORM ACTION="" METHOD="GET">
				<input type="hidden" name="subtopic" value="forum" />
				<input type="hidden" name="action" value="moved_thread" />
				<input type="hidden" name="id" value="'.$post['id'].'" />
				<strong>THREAD:</strong> '.$post['post_topic'].'
				<br/><strong>AUTHOR:</strong> '.$name[0].'
				<br/><strong>BOARD:</strong> '.$sections[$post['section']].'<br/>
				<br/><strong>Select the new board:&nbsp;</strong><SELECT NAME=sektion>';
				foreach($sections as $id => $section) { $main_content .= '<OPTION value="'.$id.'">'.$section.'</OPTION>'; } $main_content .= '</SELECT>
				<INPUT TYPE="submit" VALUE="Move Thread"></FORM> 
				<form action="?subtopic=forum&action=show_board&id='.$post['section'].'" method="POST">
				<input type="submit" value="Cancel"></form></td></tr></table></td></tr></table>';							
			}
		}
		else
			$main_content .= 'Post with ID '.$id.' does not exist.';
	}
	else
		$main_content .= 'You are not logged in or you are not moderator.';
}

if($action == 'moved_thread') 
{
	if($logged && $group_id_of_acc_logged >= $group_not_blocked)
	{
		$id = (int) $_REQUEST['id'];
		$board = (int) $_REQUEST['sektion'];
		$post = $SQL->query("SELECT `id`, `sticky`, `first_post`, `section` FROM `z_forum` WHERE `id` = ".$id." LIMIT 1")->fetch();
		if($post['id'] == $id)
		{
			if($post['id'] == $post['first_post'])
			{	
				$SQL->query("UPDATE `z_forum` SET `section` = ".$board." WHERE `id` = ".$post['id']."") or die(mysql_error());
				$nPost = $SQL->query( 'SELECT `section` FROM `z_forum` WHERE `id` = \''.$id.'\' LIMIT 1;' )->fetch();
				header('Location: ?subtopic=forum&action=show_board&id='.$nPost['section']);
			} 
		}
		else
			$main_content .= 'Post with ID '.$id.' does not exist.';
	}
	else
		$main_content .= 'You are not logged in or you are not moderator.';
}

##>

if($action == 'remove_post')
{
    if($logged && $group_id_of_acc_logged >= $group_not_blocked)
    {
        $id = (int) $_REQUEST['id'];
        $post = $SQL->query("SELECT `id`, `first_post`, `section` FROM `z_forum` WHERE `id` = ".$id." LIMIT 1")->fetch();
        if($post['id'] == $id)
        {
            if($post['id'] == $post['first_post'])
            {
                $SQL->query("DELETE FROM `z_forum` WHERE `first_post` = ".$post['id']);
                header('Location: ?subtopic=forum&action=show_board&id='.$post['section']);
            }
            else
            {
                $post_page = $SQL->query("SELECT COUNT(`z_forum`.`id`) AS posts_count FROM `players`, `z_forum` WHERE `players`.`id` = `z_forum`.`author_guid` AND `z_forum`.`id` < ".$id." AND `z_forum`.`first_post` = ".(int) $post['first_post'])->fetch();
                $page = (int) ceil($post_page['posts_count'] / $threads_per_page) - 1;
                $SQL->query("DELETE FROM `z_forum` WHERE `id` = ".$post['id']);
                header('Location: ?subtopic=forum&action=show_thread&id='.$post['first_post'].'&page='.(int) $page);
            }
        }
        else
            $main_content .= 'Post with ID '.$id.' does not exist.';
    }
    else
        $main_content .= 'You are not logged in or you are not moderator.';
}

if($action  == 'new_post')
{
    if($logged)
    {
        if(canPost($account_logged) || $group_id_of_acc_logged >= $group_not_blocked)
        {
            $thread_id = (int) $_REQUEST['thread_id'];
            if(isThreadOpen($thread_id)) {           
            $players_from_account = $SQL->query("SELECT `players`.`name`, `players`.`id` FROM `players` WHERE `players`.`account_id` = ".(int) $account_logged->getId())->fetchAll();
            $thread_id = (int) $_REQUEST['thread_id'];
            $thread = $SQL->query("SELECT `z_forum`.`post_topic`, `z_forum`.`id`, `z_forum`.`section` FROM `z_forum` WHERE `z_forum`.`id` = ".(int) $thread_id." AND `z_forum`.`first_post` = ".(int) $thread_id." LIMIT 1")->fetch();
            $main_content .= '<a href="?subtopic=forum">Boards</a> >> <a href="?subtopic=forum&action=show_board&id='.$thread['section'].'">'.$sections[$thread['section']].'</a> >> <a href="?subtopic=forum&action=show_thread&id='.$thread_id.'">'.$thread['post_topic'].'</a> >> <b>Post new reply</b><br /><h3>'.$thread['post_topic'].'</h3>';
            if(isset($thread['id']))
            {
                $quote = (int) $_REQUEST['quote'];
                $text = stripslashes(trim(codeLower($_REQUEST['text'])));
                $char_id = (int) $_REQUEST['char_id'];
                $post_topic = stripslashes(trim($_REQUEST['topic']));
                $smile = (int) $_REQUEST['smile'];
                $saved = false;
                if(isset($_REQUEST['quote']))
                {
                    $quoted_post = $SQL->query("SELECT `players`.`name`, `z_forum`.`post_text`, `z_forum`.`post_date` FROM `players`, `z_forum` WHERE `players`.`id` = `z_forum`.`author_guid` AND `z_forum`.`id` = ".(int) $quote)->fetchAll();
                    if(isset($quoted_post[0]['name']))
                        $text = '[i]Originally posted by '.$quoted_post[0]['name'].' on '.date('d.m.y H:i:s', $quoted_post[0]['post_date']).':[/i][quote]'.$quoted_post[0]['post_text'].'[/quote]';
                }
                elseif(isset($_REQUEST['save']))
                {
                    $lenght = 0;
                    for($i = 0; $i <= strlen($text); $i++)
                    {
                        if(ord($text[$i]) >= 33 && ord($text[$i]) <= 126)
                            $lenght++;
                    }
                    if($lenght < 1 || strlen($text) > 15000)
                        $errors[] = 'Too short or too long post (short: '.$lenght.' long: '.strlen($text).' letters). Minimum 1 letter, maximum 15000 letters.';
                    if($char_id == 0)
                        $errors[] = 'Please select a character.';
                    $player_on_account == false;
                    if(count($errors) == 0)
                    {
                        foreach($players_from_account as $player)
                            if($char_id == $player['id'])
                                $player_on_account = true;
                        if(!$player_on_account)
                            $errors[] = 'Player with selected ID '.$char_id.' doesn\'t exist or isn\'t on your account';
                    }
                    if(count($errors) == 0)
                    {
                        $last_post = $account_logged->getCustomField('last_post');
                        if($last_post+$post_interval-time() > 0 && $group_id_of_acc_logged < $group_not_blocked)
                            $errors[] = 'You can post one time per '.$post_interval.' seconds. Next post after '.($last_post+$post_interval-time()).' second(s).';
                    }
                    if(count($errors) == 0)
                    {
                        $saved = true;
                        $account_logged->setCustomField('last_post', time());
                        $SQL->query("INSERT INTO `z_forum` (`id` ,`first_post` ,`last_post` ,`section` ,`replies` ,`views` ,`author_aid` ,`author_guid` ,`post_text` ,`post_topic` ,`post_smile` ,`post_date` ,`last_edit_aid` ,`edit_date`, `post_ip`) VALUES ('NULL', '".$thread['id']."', '0', '".$thread['section']."', '0', '0', '".$account_logged->getId()."', '".(int) $char_id."', ".$SQL->quote($text).", ".$SQL->quote($post_topic).", '".(int) $smile."', '".time()."', '0', '0', '".$_SERVER['REMOTE_ADDR']."')");
                        $SQL->query("UPDATE `z_forum` SET `replies`=`replies`+1, `last_post`=".time()." WHERE `id` = ".(int) $thread_id);
                        $post_page = $SQL->query("SELECT COUNT(`z_forum`.`id`) AS posts_count FROM `players`, `z_forum` WHERE `players`.`id` = `z_forum`.`author_guid` AND `z_forum`.`post_date` <= ".time()." AND `z_forum`.`first_post` = ".(int) $thread['id'])->fetch();
                        $page = (int) ceil($post_page['posts_count'] / $threads_per_page) - 1;
                        header('Location: ?subtopic=forum&action=show_thread&id='.$thread_id.'&page='.$page);
                        $main_content .= '<br />Thank you for posting.<br /><a href="?subtopic=forum&action=show_thread&id='.$thread_id.'">GO BACK TO LAST THREAD</a>';
                    }
                }
                if(!$saved)
                {
                    if(count($errors) > 0)
                    {
                        $main_content .= '<font color="red" size="2"><b>Errors occured:</b>';
                        foreach($errors as $error)
                            $main_content .= '<br />* '.$error;
                        $main_content .= '</font><br />';
                    }
                    $main_content .= '<form action="?" method="POST"><input type="hidden" name="action" value="new_post" /><input type="hidden" name="thread_id" value="'.$thread_id.'" /><input type="hidden" name="subtopic" value="forum" /><input type="hidden" name="save" value="save" /><table width="100%"><tr bgcolor="'.$config['site']['vdarkborder'].'"><td colspan="2"><font color="white"><b>Post New Reply</b></font></td></tr><tr bgcolor="'.$config['site']['darkborder'].'"><td width="180"><b>Character:</b></td><td><select name="char_id"><option value="0">(Choose character)</option>';
                    foreach($players_from_account as $player)
                    {
                        $main_content .= '<option value="'.$player['id'].'"';
                        if($player['id'] == $char_id)
                            $main_content .= ' selected="selected"';
                        $main_content .= '>'.$player['name'].'</option>';
                    }
                    $main_content .= '</select></td></tr><tr bgcolor="'.$config['site']['lightborder'].'"><td><b>Topic:</b></td><td><input type="text" name="topic" value="'.htmlspecialchars($post_topic).'" size="40" maxlength="60" /> (Optional)</td></tr>
                    <tr bgcolor="'.$config['site']['darkborder'].'"><td valign="top"><b>Message:</b><font size="1"><br />You can use:<br />[player]Nick[/player]<br />[url=http://address.com/]Address Search - Find Email and Addresses @ Address.com[/url]<br />[img]http://images.com/images3.gif[/img]<br />[code]Code[/code]<br />[b]<b>Text</b>[/b]<br />[i]<i>Text</i>[/i]<br />[u]<u>Text</u>[/u]<br />and smileys:<br />;) , :) , :D , :( , :rolleyes:<br />:cool: , :eek: , :o , :p</font></td><td><textarea rows="10" cols="40" name="text">'.htmlspecialchars($text).'</textarea><br />(Max. 15,000 letters)</td></tr>
                    <tr bgcolor="'.$config['site']['lightborder'].'"><td valign="top">Options:</td><td><label><input type="checkbox" name="smile" value="1"';
                    if($smile == 1)
                        $main_content .= ' checked="checked"';
                    $main_content .= '/>Disable Smileys in This Post </label></td></tr></table><center><input type="submit" value="Post Reply" /></center></form>';
                    $threads = $SQL->query("SELECT `players`.`name`, `z_forum`.`post_text`, `z_forum`.`post_topic`, `z_forum`.`post_smile` FROM `players`, `z_forum` WHERE `players`.`id` = `z_forum`.`author_guid` AND `z_forum`.`first_post` = ".(int) $thread_id." ORDER BY `z_forum`.`post_date` DESC LIMIT 10")->fetchAll();
                    $main_content .= '<table width="100%"><tr bgcolor="'.$config['site']['vdarkborder'].'"><td colspan="2"><font color="white"><b>Last 5 posts from thread: '.$thread['post_topic'].'</b></font></td></tr>';
                    foreach($threads as $thread)
                    {
                        if(is_int($number_of_rows / 2)) { $bgcolor = $config['site']['darkborder']; } else { $bgcolor = $config['site']['lightborder']; } $number_of_rows++;
                        $main_content .= '<tr bgcolor="'.$bgcolor.'"><td>'.$thread['name'].'</td><td>'.showPost($thread['post_topic'], $thread['post_text'], $thread['post_smile']).'</td></tr>';
                    }
                    $main_content .= '</table>';
                }
            }
            else
                $main_content .= 'Thread with ID '.$thread_id.' doesn\'t exist.';
        }
            else
            $main_content .= 'This thread is closed. You can\'t post.';
    }
        else
            $main_content .= 'Your account is banned, deleted or you don\'t have any player with level '.$level_limit.' on your account. You can\'t post.';
    }
    else
        $main_content .= 'Login first.';
}

if($action  == 'edit_post')
{
    if($logged)
    {
        if(canPost($account_logged) || $group_id_of_acc_logged >= $group_not_blocked)
        {
            $post_id = (int) $_REQUEST['id'];
            $thread = $SQL->query("SELECT `z_forum`.`author_guid`, `z_forum`.`author_aid`, `z_forum`.`first_post`, `z_forum`.`post_topic`, `z_forum`.`post_date`, `z_forum`.`post_text`, `z_forum`.`post_smile`, `z_forum`.`id`, `z_forum`.`section` FROM `z_forum` WHERE `z_forum`.`id` = ".(int) $post_id." LIMIT 1")->fetch();
            if(isThreadOpen($thread['id'])) {
            if(isset($thread['id']))
            {
                $first_post = $SQL->query("SELECT `z_forum`.`author_guid`, `z_forum`.`author_aid`, `z_forum`.`first_post`, `z_forum`.`post_topic`, `z_forum`.`post_text`, `z_forum`.`post_smile`, `z_forum`.`id`, `z_forum`.`section` FROM `z_forum` WHERE `z_forum`.`id` = ".(int) $thread['first_post']." LIMIT 1")->fetch();
                $main_content .= '<a href="?subtopic=forum">Boards</a> >> <a href="?subtopic=forum&action=show_board&id='.$thread['section'].'">'.$sections[$thread['section']].'</a> >> <a href="?subtopic=forum&action=show_thread&id='.$thread['first_post'].'">'.$first_post['post_topic'].'</a> >> <b>Edit post</b>';
                if($account_logged->getId() == $thread['author_aid'] || $group_id_of_acc_logged >= $group_not_blocked)
                {
                    $players_from_account = $SQL->query("SELECT `players`.`name`, `players`.`id` FROM `players` WHERE `players`.`account_id` = ".(int) $account_logged->getId())->fetchAll();
                    $saved = false;
                    if(isset($_REQUEST['save']))
                    {
                        $text = stripslashes(trim(codeLower($_REQUEST['text'])));
                        $char_id = (int) $_REQUEST['char_id'];
                        $post_topic = stripslashes(trim($_REQUEST['topic']));
                        $smile = (int) $_REQUEST['smile'];
                        $lenght = 0;
                        for($i = 0; $i <= strlen($post_topic); $i++)
                        {
                            if(ord($post_topic[$i]) >= 33 && ord($post_topic[$i]) <= 126)
                                $lenght++;
                        }
                        if(($lenght < 1 || strlen($post_topic) > 60) && $thread['id'] == $thread['first_post'])
                            $errors[] = 'Too short or too long topic (short: '.$lenght.' long: '.strlen($post_topic).' letters). Minimum 1 letter, maximum 60 letters.';
                        $lenght = 0;
                        for($i = 0; $i <= strlen($text); $i++)
                        {
                            if(ord($text[$i]) >= 33 && ord($text[$i]) <= 126)
                                $lenght++;
                        }
                        if($lenght < 1 || strlen($text) > 15000)
                            $errors[] = 'Too short or too long post (short: '.$lenght.' long: '.strlen($text).' letters). Minimum 1 letter, maximum 15000 letters.';
                        if($char_id == 0)
                            $errors[] = 'Please select a character.';
                        if(empty($post_topic) && $thread['id'] == $thread['first_post'])
                            $errors[] = 'Thread topic can\'t be empty.';
                        $player_on_account == false;
                        if(count($errors) == 0)
                        {
                            foreach($players_from_account as $player)
                                if($char_id == $player['id'])
                                    $player_on_account = true;
                            if(!$player_on_account)
                                $errors[] = 'Player with selected ID '.$char_id.' doesn\'t exist or isn\'t on your account';
                        }
                        if(count($errors) == 0)
                        {
                            $saved = true;
                            if($account_logged->getId() != $thread['author_aid'])
                                $char_id = $thread['author_guid'];
                            $SQL->query("UPDATE `z_forum` SET `author_guid` = ".(int) $char_id.", `post_text` = ".$SQL->quote($text).", `post_topic` = ".$SQL->quote($post_topic).", `post_smile` = ".(int) $smile.", `last_edit_aid` = ".(int) $account_logged->getId().",`edit_date` = ".time()." WHERE `id` = ".(int) $thread['id']);
                            $post_page = $SQL->query("SELECT COUNT(`z_forum`.`id`) AS posts_count FROM `players`, `z_forum` WHERE `players`.`id` = `z_forum`.`author_guid` AND `z_forum`.`post_date` <= ".$thread['post_date']." AND `z_forum`.`first_post` = ".(int) $thread['first_post'])->fetch();
                            $page = (int) ceil($post_page['posts_count'] / $threads_per_page) - 1;
                            header('Location: ?subtopic=forum&action=show_thread&id='.$thread['first_post'].'&page='.$page);
                            $main_content .= '<br />Thank you for editing post.<br /><a href="?subtopic=forum&action=show_thread&id='.$thread['first_post'].'">GO BACK TO LAST THREAD</a>';
                        }
                    }
                    else
                    {
                        $text = $thread['post_text'];
                        $char_id = (int) $thread['author_guid'];
                        $post_topic = $thread['post_topic'];
                        $smile = (int) $thread['post_smile'];
                    }
                    if(!$saved)
                    {
                        if(count($errors) > 0)
                        {
                            $main_content .= '<br /><font color="red" size="2"><b>Errors occured:</b>';
                            foreach($errors as $error)
                                $main_content .= '<br />* '.$error;
                            $main_content .= '</font>';
                        }
                        $main_content .= '<br /><form action="?" method="POST"><input type="hidden" name="action" value="edit_post" /><input type="hidden" name="id" value="'.$post_id.'" /><input type="hidden" name="subtopic" value="forum" /><input type="hidden" name="save" value="save" /><table width="100%"><tr bgcolor="'.$config['site']['vdarkborder'].'"><td colspan="2"><font color="white"><b>Edit Post</b></font></td></tr><tr bgcolor="'.$config['site']['darkborder'].'"><td width="180"><b>Character:</b></td><td><select name="char_id"><option value="0">(Choose character)</option>';
                        foreach($players_from_account as $player)
                        {
                            $main_content .= '<option value="'.$player['id'].'"';
                            if($player['id'] == $char_id)
                                $main_content .= ' selected="selected"';
                            $main_content .= '>'.$player['name'].'</option>';
                        }
                        $main_content .= '</select></td></tr><tr bgcolor="'.$config['site']['lightborder'].'"><td><b>Topic:</b></td><td><input type="text" value="'.htmlspecialchars($post_topic).'" name="topic" size="40" maxlength="60" /> (Optional)</td></tr>
                        <tr bgcolor="'.$config['site']['darkborder'].'"><td valign="top"><b>Message:</b><font size="1"><br />You can use:<br />[player]Nick[/player]<br />[url=http://address.com/]Address Search - Find Email and Addresses @ Address.com[/url]<br />[img]http://images.com/images3.gif[/img]<br />[code]Code[/code]<br />[b]<b>Text</b>[/b]<br />[i]<i>Text</i>[/i]<br />[u]<u>Text</u>[/u]<br />and smileys:<br />;) , :) , :D , :( , :rolleyes:<br />:cool: , :eek: , :o , :p</font></td><td><textarea rows="10" cols="40" name="text">'.htmlspecialchars($text).'</textarea><br />(Max. 15,000 letters)</td></tr>
                        <tr bgcolor="'.$config['site']['lightborder'].'"><td valign="top">Options:</td><td><label><input type="checkbox" name="smile" value="1"';
                        if($smile == 1)
                            $main_content .= ' checked="checked"';
                        $main_content .= '/>Disable Smileys in This Post </label></td></tr></table><center><input type="submit" value="Save Post" /></center></form>';
                    }
                }
                else
                    $main_content .= '<br />You are not an author of this post.';
            }
            else
                $main_content .= '<br />Post with ID '.$post_id.' doesn\'t exist.';
        }
        else
            $main_content .= '<br />Thread is closed. You can\'t post.';
        }
        else
            $main_content .= '<br />Your account is banned, deleted or you don\'t have any player with level '.$level_limit.' on your account. You can\'t post.';
    }
    else
        $main_content .= '<br />Login first.';
}

if($action == 'new_topic')
{
    if($logged)
    {
        if(canPost($account_logged) || $group_id_of_acc_logged >= $group_not_blocked)
        {
            $players_from_account = $SQL->query("SELECT `players`.`name`, `players`.`id` FROM `players` WHERE `players`.`account_id` = ".(int) $account_logged->getId())->fetchAll();
            $section_id = (int) $_REQUEST['section_id'];
            $main_content .= '<a href="?subtopic=forum">Boards</a> >> <a href="?subtopic=forum&action=show_board&id='.$section_id.'">'.$sections[$section_id].'</a> >> <b>Post new thread</b><br />';
            if(isset($sections[$section_id]))
            {
                if($section_id == 1 && $group_id_of_acc_logged < $group_not_blocked)
                    $errors[] = 'Only moderators and admins can post on news board.';
                $quote = (int) $_REQUEST['quote'];
                $text = stripslashes(trim(codeLower($_REQUEST['text'])));
                $char_id = (int) $_REQUEST['char_id'];
                $post_topic = stripslashes(trim($_REQUEST['topic']));
                $smile = (int) $_REQUEST['smile'];
                $saved = false;
                if(isset($_REQUEST['save']))
                {
                    $lenght = 0;
                    for($i = 0; $i <= strlen($post_topic); $i++)
                    {
                        if(ord($post_topic[$i]) >= 33 && ord($post_topic[$i]) <= 126)
                            $lenght++;
                    }
                    if($lenght < 1 || strlen($post_topic) > 60)
                        $errors[] = 'Too short or too long topic (short: '.$lenght.' long: '.strlen($post_topic).' letters). Minimum 1 letter, maximum 60 letters.';
                    $lenght = 0;
                    for($i = 0; $i <= strlen($text); $i++)
                    {
                        if(ord($text[$i]) >= 33 && ord($text[$i]) <= 126)
                            $lenght++;
                    }
                    if($lenght < 1 || strlen($text) > 15000)
                        $errors[] = 'Too short or too long post (short: '.$lenght.' long: '.strlen($text).' letters). Minimum 1 letter, maximum 15000 letters.';
                    if($char_id == 0)
                        $errors[] = 'Please select a character.';
                    $player_on_account == false;
                    if(count($errors) == 0)
                    {
                        foreach($players_from_account as $player)
                            if($char_id == $player['id'])
                                $player_on_account = true;
                        if(!$player_on_account)
                            $errors[] = 'Player with selected ID '.$char_id.' doesn\'t exist or isn\'t on your account';
                    }
                    if(count($errors) == 0)
                    {
                        $last_post = $account_logged->getCustomField('last_post');
                        if($last_post+$post_interval-time() > 0 && $group_id_of_acc_logged < $group_not_blocked)
                            $errors[] = 'You can post one time per '.$post_interval.' seconds. Next post after '.($last_post+$post_interval-time()).' second(s).';
                    }
                    if(count($errors) == 0)
                    {
                        $saved = true;
                        $account_logged->setCustomField('last_post', time());
                        $SQL->query("INSERT INTO `z_forum` (`id` ,`first_post` ,`last_post` ,`section` ,`replies` ,`views` ,`author_aid` ,`author_guid` ,`post_text` ,`post_topic` ,`post_smile` ,`post_date` ,`last_edit_aid` ,`edit_date`, `post_ip`) VALUES ('NULL', '0', '".time()."', '".(int) $section_id."', '0', '0', '".$account_logged->getId()."', '".(int) $char_id."', ".$SQL->quote($text).", ".$SQL->quote($post_topic).", '".(int) $smile."', '".time()."', '0', '0', '".$_SERVER['REMOTE_ADDR']."')");
                        $thread_id = $SQL->lastInsertId();
                        $SQL->query("UPDATE `z_forum` SET `first_post`=".(int) $thread_id." WHERE `id` = ".(int) $thread_id);
                        header('Location: ?subtopic=forum&action=show_thread&id='.$thread_id);
                        $main_content .= '<br />Thank you for posting.<br /><a href="?subtopic=forum&action=show_thread&id='.$thread_id.'">GO BACK TO LAST THREAD</a>';
                    }
                }
                if(!$saved)
                {
                    if(count($errors) > 0)
                    {
                        $main_content .= '<font color="red" size="2"><b>Errors occured:</b>';
                        foreach($errors as $error)
                            $main_content .= '<br />* '.$error;
                        $main_content .= '</font><br />';
                    }
                    $main_content .= '<form action="?" method="POST"><input type="hidden" name="action" value="new_topic" /><input type="hidden" name="section_id" value="'.$section_id.'" /><input type="hidden" name="subtopic" value="forum" /><input type="hidden" name="save" value="save" /><table width="100%"><tr bgcolor="'.$config['site']['vdarkborder'].'"><td colspan="2"><font color="white"><b>Post New Reply</b></font></td></tr><tr bgcolor="'.$config['site']['darkborder'].'"><td width="180"><b>Character:</b></td><td><select name="char_id"><option value="0">(Choose character)</option>';
                    foreach($players_from_account as $player)
                    {
                        $main_content .= '<option value="'.$player['id'].'"';
                        if($player['id'] == $char_id)
                            $main_content .= ' selected="selected"';
                        $main_content .= '>'.$player['name'].'</option>';
                    }
                    $main_content .= '</select></td></tr><tr bgcolor="'.$config['site']['lightborder'].'"><td><b>Topic:</b></td><td><input type="text" name="topic" value="'.htmlspecialchars($post_topic).'" size="40" maxlength="60" /> (Optional)</td></tr>
                    <tr bgcolor="'.$config['site']['darkborder'].'"><td valign="top"><b>Message:</b><font size="1"><br />You can use:<br />[player]Nick[/player]<br />[url=http://address.com/]Address Search - Find Email and Addresses @ Address.com[/url]<br />[img]http://images.com/images3.gif[/img]<br />[code]Code[/code]<br />[b]<b>Text</b>[/b]<br />[i]<i>Text</i>[/i]<br />[u]<u>Text</u>[/u]<br />and smileys:<br />;) , :) , :D , :( , :rolleyes:<br />:cool: , :eek: , :o , :p</font></td><td><textarea rows="10" cols="40" name="text">'.htmlspecialchars($text).'</textarea><br />(Max. 15,000 letters)</td></tr>
                    <tr bgcolor="'.$config['site']['lightborder'].'"><td valign="top">Options:</td><td><label><input type="checkbox" name="smile" value="1"';
                    if($smile == 1)
                        $main_content .= ' checked="checked"';
                    $main_content .= '/>Disable Smileys in This Post </label></td></tr></table><center><input type="submit" value="Post Thread" /></center></form>';
                }
            }
            else
                $main_content .= 'Board with ID '.$board_id.' doesn\'t exist.';
        }
        else
            $main_content .= 'Your account is banned, deleted or you don\'t have any player with level '.$level_limit.' on your account. You can\'t post.';
    }
    else
        $main_content .= 'Login first.';
}
?>