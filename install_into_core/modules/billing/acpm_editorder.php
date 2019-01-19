<?php
require("ismodule.php");
require("includes/functions_class.php");
require("modules/$modfolder/functions_billing.php");
$do = $_GET['do'];
if ($do == "edit")
{
$id = $_POST['id'];
$id = (int)$id;
$notes = mysqli_real_escape_string($xrf_db, $_POST['notes']);

xrfb_update_order($xrf_db, $id);

mysqli_query($xrf_db, "UPDATE b_orders SET notes = '$notes' WHERE id = '$id'") or die(mysqli_error($xrf_db)); 

xrf_go_redir("acp_module_panel.php?modfolder=$modfolder&modpanel=vieworder&id=$id","Order edited.",2);
}
else
{
$passid = $_GET['passid'];
$id=(int)$passid;
echo "<b>Edit Order</b><p>";

$query="SELECT * FROM b_orders WHERE id='$id'";
$result=mysqli_query($xrf_db, $query) or die(mysqli_error($xrf_db));
$uid=xrf_mysql_result($result,0,"uid");
$notes=xrf_mysql_result($result,0,"notes");
$lname=xrf_get_lname($xrf_db, $uid);
$fname=xrf_get_fname($xrf_db, $uid);

echo "<form action=\"acp_module_panel.php?modfolder=$modfolder&modpanel=editorder&do=edit\" method=\"POST\">
<table><tr><td><b>Customer:</b></td><td>$lname, $fname <input type=\"hidden\" name=\"id\" value=\"$id\"><input type=\"submit\" value=\"Save Changes\"></td></tr>
<tr><td><b>Order Notes:</b></td><td><textarea name=\"notes\" rows=\"8\" cols=\"50\">$notes</textarea></td></tr>
</table></form>";
}
?>