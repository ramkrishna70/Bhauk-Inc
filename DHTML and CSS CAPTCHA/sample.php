<?PHP
/**
* @desc This sample shows using as_snipercaptcha.php class for rendering
* CAPTCHA code and checking user input.
* @Author Alexander Selifonov <as-works [@] narod.ru>
* modified 28.02.2008 (dd.mm.yyyy)
**/
require_once('as_snipercaptcha.php');

$captcha = new CSniperCaptcha(5,'btnsubmit','UserPassedTest()','UserFailedTest()');

?>
<HTML><BODY>
<H4>As_SniperCaptcha using sample</H4>

<?
CSniperCaptcha::DrawRefs('','');
?>
<script language="javascript">
function UserPassedTest() {
  asGetObj("testresult").innerHTML = "Yes ! I am a real man !";
}
function UserFailedTest() {
  asGetObj("testresult").innerHTML = "Oops ! I have failed. I must be a SPAM bot ...";
}
</script>

<?

$self = $_SERVER['PHP_SELF'];
$result = 'Here will be the checking result...';
$clr = '#C0C0FF';
if(!empty($_POST)) {
  $good = $captcha->CheckPassed();
  $clr = $good ? '#A0FFA0' : '#FFA0A0';
  $result = "Humanity test ".($good ? 'successfully passed, I think You are real Human !' :'NOT PASSED !!!');
}
echo "<div style='background-color:$clr; border: 1px solid #505050; text-align:center'>$result</div>";
?>
<br />
<b>CAPTCHA test:<br/>Click marked boxes until progress bar is 100% filled</b>
<table width='300'><tr><form method='POST' name='bt_captchatest' action='<?=$self?>'>
<td><div id='cpholder' style='background-color:#C0C0FF; border: 1px solid #A0A0A8;'>
<? $captcha->Draw(8,3,12); ?>
</div></td>
<td width='100%'><input type='submit' name='submit' id='btnsubmit' value='Check the code' disabled /></td></tr></form>
<tr><td colspan=2 bgcolor'#E0E0FF' id='testresult'>&nbsp;</td></tr>
</table>
<?
  $captcha->DrawJsCode();
?>
</BODY></HTML>
