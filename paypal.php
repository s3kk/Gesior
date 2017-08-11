<?PHP
$content = file_get_contents("paypal/paypal.htm");
if($content != FALSE)
	$main_content .= $content;
else
	$main_content .= 'Can not load file <b>paypal.htm</b> or file is empty.';
?>
