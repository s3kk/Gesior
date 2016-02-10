<?php
if(!$logged)
if($action == "logout")
$main_content .= '<div class="TableContainer" > <table class="Table1" cellpadding="0" cellspacing="0" > <div class="CaptionContainer" > <div class="CaptionInnerContainer" > <span class="CaptionEdgeLeftTop" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></span> <span class="CaptionEdgeRightTop" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></span> <span class="CaptionBorderTop" style="background-image:url('.$layout_name.'/images/content/table-headline-border.gif);" ></span> <span class="CaptionVerticalLeft" style="background-image:url('.$layout_name.'/images/content/box-frame-vertical.gif);" /></span> <div class="Text" >Logout Successful</div> <span class="CaptionVerticalRight" style="background-image:url('.$layout_name.'/images/content/box-frame-vertical.gif);" /></span> <span class="CaptionBorderBottom" style="background-image:url('.$layout_name.'/images/content/table-headline-border.gif);" ></span> <span class="CaptionEdgeLeftBottom" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></span> <span class="CaptionEdgeRightBottom" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></span> </div> </div> <tr> <td> <div class="InnerTableContainer" > <table style="width:100%;" ><tr><td>You have logged out of your '.$config['server']['serverName'].' account. In order to view your account you need to <a href="?subtopic=accountmanagement" >log in</a> again.</td></tr> </table> </div> </table></div></td></tr>';
else
$main_content .= 'Please enter your account name and your password.<br/><a href="?subtopic=createaccount" >Create an account</a> if you do not have one yet.<br/><br/><form action="?subtopic=accountmanagement" method="post" ><div class="TableContainer" > <table class="Table1" cellpadding="0" cellspacing="0" > <div class="CaptionContainer" > <div class="CaptionInnerContainer" > <span class="CaptionEdgeLeftTop" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></span> <span class="CaptionEdgeRightTop" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></span> <span class="CaptionBorderTop" style="background-image:url('.$layout_name.'/images/content/table-headline-border.gif);" ></span> <span class="CaptionVerticalLeft" style="background-image:url('.$layout_name.'/images/content/box-frame-vertical.gif);" /></span> <div class="Text" >Account Login</div> <span class="CaptionVerticalRight" style="background-image:url('.$layout_name.'/images/content/box-frame-vertical.gif);" /></span> <span class="CaptionBorderBottom" style="background-image:url('.$layout_name.'/images/content/table-headline-border.gif);" ></span> <span class="CaptionEdgeLeftBottom" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></span> <span class="CaptionEdgeRightBottom" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></span> </div> </div> <tr> <td> <div class="InnerTableContainer" > <table style="width:100%;" ><tr><td class="LabelV" ><span >Account Name:</span></td><td style="width:100%;" ><input type="password" name="account_login" SIZE="10" maxlength="10" ></td></tr><tr><td class="LabelV" ><span >Password:</span></td><td><input type="password" name="password_login" size="30" maxlength="29" ></td></tr> </table> </div> </table></div></td></tr><br/><table width="100%" ><tr align="center" ><td><table border="0" cellspacing="0" cellpadding="0" ><tr><td style="border:0px;" ><div class="BigButton" style="background-image:url('.$layout_name.'/images/buttons/sbutton.gif)" ><div &#111;nmouseover="MouseOverBigButton(this);" &#111;nmouseout="MouseOutBigButton(this);" ><div class="BigButtonOver" style="background-image:url('.$layout_name.'/images/buttons/sbutton_over.gif);" ></div><input class="ButtonText" type="image" name="Submit" alt="Submit" src="'.$layout_name.'/images/buttons/_sbutton_submit.gif" ></div></div></td><tr></form></table></td><td><table border="0" cellspacing="0" cellpadding="0" ><form action="?subtopic=lostaccount" method="post" ><tr><td style="border:0px;" ><div class="BigButton" style="background-image:url('.$layout_name.'/images/buttons/sbutton.gif)" ><div &#111;nmouseover="MouseOverBigButton(this);" &#111;nmouseout="MouseOutBigButton(this);" ><div class="BigButtonOver" style="background-image:url('.$layout_name.'/images/buttons/sbutton_over.gif);" ></div><input class="ButtonText" type="image" name="Account lost?" alt="Account lost?" src="'.$layout_name.'/images/buttons/_sbutton_accountlost.gif" ></div></div></td></tr></form></table></td></tr></table>';
else
{
$main_content .= '<table width="100%" border="0" cellpadding="4" cellspacing="1">
<tbody><tr>
 <td class="white" colspan="3" bgcolor="#505050"><span class="style4">Detalhes da bonifica&ccedil;&atilde;o de Points.</span></td>

</tr>
<tr bgcolor="#f1e0c6"><td width="35%"><b>Doa&ccedil;&atilde;o</b></td><td width="35%"><b>Points</b></td></tr>
<tr bgcolor="#d4c0a1"><td><img src="layouts/tibiacom/images/content/bullet.gif">R$5,00</td><td><img src="layouts/tibiacom/images/content/bullet.gif"> 5 Points</td></tr>

<tr bgcolor="#f1e0c6"><td><img src="layouts/tibiacom/images/content/bullet.gif">R$10,00</td><td><img src="layouts/tibiacom/images/content/bullet.gif"> 10 Points</td></tr>
<tr bgcolor="#d4c0a1"><td><img src="layouts/tibiacom/images/content/bullet.gif">R$20,00</td><td><img src="layouts/tibiacom/images/content/bullet.gif"> 20 Points</td></tr>
<tr bgcolor="#f1e0c6"><td><img src="layouts/tibiacom/images/content/bullet.gif">R$40,00</td><td><img src="layouts/tibiacom/images/content/bullet.gif"> 40 Points</td></tr>
<tr bgcolor="#d4c0a1"><td><img src="layouts/tibiacom/images/content/bullet.gif">R$60,00</td><td><img src="layouts/tibiacom/images/content/bullet.gif"> 60 Points</td></tr>
<tr bgcolor="#f1e0c6"><td><center><img src="layouts/tibiacom/images/content/bullet.gif"> E assim por diante!!! <img src="layouts/tibiacom/images/content/bullet.gif"></center><td><center><img src="layouts/tibiacom/images/content/bullet.gif"> E assim por diante!!! <img src="layouts/tibiacom/images/content/bullet.gif"></center></td></tr>
</tbody></table></br>
';
$main_content .= '
<form target="pagseguro" method="post" action="https://pagseguro.uol.com.br/checkout/checkout.jhtml">
<input type="hidden" name="email_cobranca" value="'. $config['pagseguro']['email']. '">
<input type="hidden" name="tipo" value="CP">
<input type="hidden" name="moeda" value="BRL">
<input type="hidden" name="item_id_1" value="1">
<input type="hidden" name="item_descr_1" value="Pontos na account de nome: '.$account_logged->getCustomField("name").'">
<input type="hidden" name="item_frete_1" value="0">
<input type="hidden" name="item_peso_1" value="0">
<input type="hidden" name="ref_transacao" value="'.$account_logged->getCustomField("name").'">
<table border="0" cellpadding="4" cellspacing="1" width="100%" id="#estilo"><tbody>
<tr bgcolor="#505050" class="white">
<th colspan="2"><strong>Escolha a quantidade de pontos que deseja DONATAR.</strong></th>
</tr>
<tr bgcolor="#d4c0a1">
<td width="10%">Sua conta</td>
<td><strong>'.$account_logged->getCustomField("name").'</strong></td>
</tr>
<tr bgcolor="#d4c0a1">
<td width="10%">Pontos</td>
<td>
<input type="number" ng-model="get_points" min="1" size="5" maxlength="5">
<input name="item_valor_1" type="hidden" value="{{get_points * 100}}" size="5" maxlength="5">
<input name="item_quant_1" type="hidden" value="1" size="1" maxlength="1">
</td>
</tr>
<tr bgcolor="#d4c0a1">
<td colspan="2">
<input type="image" src="https://p.simg.uol.com.br/out/pagseguro/i/botoes/carrinhoproprio/btnFinalizar.jpg" name="submit" alt="Pague com PagSeguro - é rápido, grátis e seguro!" />
</td>
</tr>
</tbody></table></form>
<b><span style="color:#ff0000;">OBS:</span></b> Os pontos são entregues <b>automáticamente</b> logo após a <u>aprovação</u> do seu pagamento pelo PagSeguro, ou seja, pagou e foi aprovado pontos depositados.
<?php } ?>'; } ?>