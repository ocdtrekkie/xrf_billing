<?php
require_once("includes/global_req_login.php");
require_once("includes/functions_get.php");
require_once("includes/header.php");

if ($xrf_myulevel < 4)
{
xrf_go_redir("index.php","Invalid permissions.",2);
}
else
{

$categories_query="CREATE TABLE newb_categories AS SELECT * FROM b_categories";
mysqli_query($xrf_db, $categories_query);

echo "Categories built.";

$charges_query="CREATE TABLE IF NOT EXISTS `newb_charges` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `customer` varchar(128) NOT NULL COMMENT 'Email address of customer',
  `oid` int(8) NOT NULL COMMENT 'Order id',
  `iid` int(8) NOT NULL COMMENT 'Item id',
  `amt` int(32) NOT NULL COMMENT 'Amount for this item',
  `quantity` int(8) NOT NULL DEFAULT '1' COMMENT 'Quantity of items at cost listed',
  `status` varchar(1) COLLATE utf8_unicode_ci NOT NULL COMMENT 'W = Waived',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Individual charges on orders';";
mysqli_query($xrf_db, $charges_query);

$charges_data_query="SELECT * FROM b_charges";
$charges_data_result=mysqli_query($xrf_db, $charges_data_query);
$charges_num=mysqli_num_rows($charges_data_result);

$qq=0;
while ($qq < $charges_num) {
	$id=xrf_mysql_result($charges_data_result,$qq,"id");
	$uid=xrf_mysql_result($charges_data_result,$qq,"uid");
	$oid=xrf_mysql_result($charges_data_result,$qq,"oid");
	$iid=xrf_mysql_result($charges_data_result,$qq,"iid");
	$amt=xrf_mysql_result($charges_data_result,$qq,"amt");
	$quantity=xrf_mysql_result($charges_data_result,$qq,"quantity");
	$status=xrf_mysql_result($charges_data_result,$qq,"status");
	$customer=xrf_get_fname($xrf_db, $uid) . " " . xrf_get_lname($xrf_db, $uid);
	
	$charge_insert_query="INSERT INTO newb_charges (id, customer, oid, iid, amt, quantity, status) VALUES('$id', \"$customer\", '$oid', '$iid', '$amt', '$quantity', '$status')";
	mysqli_query($xrf_db, $charge_insert_query) or die(mysqli_error($xrf_db));
	
	$qq++;
}

echo "Charges built.";

$config_query="CREATE TABLE newb_config AS SELECT * FROM b_config";
mysqli_query($xrf_db, $config_query);

echo "Config built.";

$inventory_query="CREATE TABLE newb_inventory AS SELECT * FROM b_inventory";
mysqli_query($xrf_db, $inventory_query);

echo "Inventory built.";

$orders_query="CREATE TABLE IF NOT EXISTS `newb_orders` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `customer` varchar(128) NOT NULL COMMENT 'Email address of customer',
  `date` varchar(32) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Date of billing yyyy-mm-dd',
  `aid` int(8) NOT NULL DEFAULT '1' COMMENT 'User ID of Associate',
  `notes` text COLLATE utf8_unicode_ci NOT NULL,
  `amt_taxes` int(32) NOT NULL DEFAULT '0',
  `amt_due` int(32) NOT NULL DEFAULT '0',
  `amt_paid` int(32) NOT NULL DEFAULT '0',
  `closed` int(1) NOT NULL DEFAULT '0' COMMENT 'If 0, order is open.  If 1, order is closed.',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
mysqli_query($xrf_db, $orders_query);

$orders_data_query="SELECT * FROM b_orders";
$orders_data_result=mysqli_query($xrf_db, $orders_data_query);
$orders_num=mysqli_num_rows($orders_data_result);

$qq=0;
while ($qq < $orders_num) {
	$id=xrf_mysql_result($orders_data_result,$qq,"id");
	$uid=xrf_mysql_result($orders_data_result,$qq,"uid");
	$date=substr(xrf_mysql_result($orders_data_result,$qq,"date"), 0, 10);
	$aid=xrf_mysql_result($orders_data_result,$qq,"aid");
	$notes=xrf_mysql_result($orders_data_result,$qq,"notes");
	$amt_taxes=xrf_mysql_result($orders_data_result,$qq,"amt_taxes");
	$amt_due=xrf_mysql_result($orders_data_result,$qq,"amt_due");
	$amt_paid=xrf_mysql_result($orders_data_result,$qq,"amt_paid");
	$closed=xrf_mysql_result($orders_data_result,$qq,"closed");
	$customer=xrf_get_fname($xrf_db, $uid) . " " . xrf_get_lname($xrf_db, $uid);
	if ($aid == 16) $aid = 3;
	if ($aid == 133) $aid = 2;
	
	$order_insert_query="INSERT INTO newb_orders (id, customer, date, aid, notes, amt_taxes, amt_due, amt_paid, closed) VALUES('$id', \"$customer\", '$date', '$aid', \"$notes\", '$amt_taxes', '$amt_due', '$amt_paid', '$closed')";
	mysqli_query($xrf_db, $order_insert_query) or die(mysqli_error($xrf_db));
	
	$qq++;
}

echo "Orders built.";

echo "New tables built.";

}

require_once("includes/footer.php");
?>