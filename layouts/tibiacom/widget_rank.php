<?php
$skills = $SQL->query('SELECT * FROM players WHERE deleted = 0 AND group_id < '.$config['site']['players_group_id_block'].' AND account_id != 1 ORDER BY level DESC LIMIT 5');
?>
<style type="text/css" media="all">
  .Toplevelbox {
    position: relative;
    margin-bottom: 10px;
    width: 180px;
    height: 200px;
  }
  .top_level {
    position: absolute;
    top: 30px;
    left: 6px;
    height: 180px;
    width: 168px;
    z-index: 20;
    text-align: center;
    padding-top: 6px;
    font-family: Tahoma, Geneva, sans-serif;
    font-size: 9.2pt;
    color: #FFF;
    font-weight: bold;
    text-align: right;
    text-decoration: inherit;
    text-shadow: 0.1em 0.1em #333
  }

  #Topbar a {
  text-decoration: none;
  cursor: auto;
  }
  a.topfont {
    font-family: Verdana, Arial, Helvetica; 
    font-size: 13px; 
    color: #0F0;
    text-decoration: none
  }
  a:hover.topfont {
    font-family: Verdana, Arial, Helvetica; 
    font-size: 13px;  
    color: #CCC; 
    text-decoration:none
  }
</style>

<div id="Topbar" class="Toplevelbox" style="background-image:url(<?PHP echo $layout_name; ?>/images/top_level.png);">
  <div class="top_level" style="background:url(<?PHP echo $layout_name; ?>/images/bg_top.png)" align="left">
    <?php 
    $a = 1;
    foreach($skills as $skill) {
      echo '<div align="left"><a href="?subtopic=characters&name='.$skill['name'].'" class="topfont">
        <font color="#CCC">&nbsp;&nbsp;&nbsp;&nbsp;'.$a.' - </font>'.$skill['name'].'
        <br>
        <small><font color="white">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Level: ('.$skill['level'].')</font></small>
        <br>
      </a>
      </div>';
      $a++;
    }
    ?>
    <div class="Bottom" style="background-image:url(http://tremera-global.com/templates/tibia/images/global/general/box-bottom.gif); left:-5px;">
    </div>
  </div>
</div>