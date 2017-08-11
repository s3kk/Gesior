<?
$bloquiados = array(";","\"","%","'","+","#","$","--","==","webzen"); 
foreach($_POST as $valor)
{
	foreach($bloquiados as $bloquiados2)
	{
		if(substr_count(strtolower($valor), strtolower($bloquiados2)) > 0) 
		{
		  die("<div align=\"center\">
  <p><br>
    <p>&nbsp;</p>
  <p>&nbsp;</p>
    <img src=\"images/no-page.gif\" /><br />
    <br />
      <span class=\"textbox style20\">N&atilde;o use Caracteres Especiais! </span></p>
  <p><br />
    <a href=\"javascript: history.back(-1);\" class=\"style30\">Voltar</a></p>
</div>");
		}
	}
}
foreach($_GET as $valor)
{
	foreach($bloquiados as $bloquiados2)
	{
		if(substr_count(strtolower($valor), strtolower($bloquiados2)) > 0) 
		{
		  die("<div align=\"center\">
  <p><br>
    <p>&nbsp;</p>
  <p>&nbsp;</p>
    <img src=\"images/no-page.gif\" /><br />
    <br />
      <span class=\"textbox style20\">N&atilde;o use Caracteres Especiais! </span></p>
  <p><br />
    <a href=\"javascript: history.back(-1);\" class=\"style30\">Voltar</a></p>
</div>");
		}
	}
}
foreach($_COOKIE as $valor)
{
	foreach($bloquiados as $bloquiados2)
	{
		if(substr_count(strtolower($valor), strtolower($bloquiados2)) > 0) 
		{
		  die("<div align=\"center\">
  <p><br>
    <p>&nbsp;</p>
  <p>&nbsp;</p>
    <img src=\"images/no-page.gif\" /><br />
    <br />
      <span class=\"textbox style20\">N&atilde;o use Caracteres Especiais! </span></p>
  <p><br />
    <a href=\"javascript: history.back(-1);\" class=\"style30\">Voltar</a></p>
</div>");
		}
	}
} ?>