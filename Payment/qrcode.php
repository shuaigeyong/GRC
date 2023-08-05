<?php
require_once 'lib/pdo_db.php';
require_once 'phpqrcode/qrlib.php';
$path = 'images/';
$qrcode = $path.time().".png";

$stmt = $dbh->query("SELECT * FROM customers ORDER BY created_time DESC LIMIT 1");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach($rows as $row){
    $custInfo = $row['cardHolderName'];
    $time = $row['created_time'];
}

$text = "$custInfo$time";
QRcode :: png($text, $qrcode, 'H', 4, 4);
echo "<img src='".$qrcode."'>";
?>
