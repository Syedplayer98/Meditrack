<?php

include 'database.php';
include 'C:/xampp/php/lib/phpqrcode/qrlib.php';

define('const_issue_custom_fields', 10);

function issuePackage(){

    $from_address = $_POST['from'];
    $package_name = $_POST['name'];
    $quantity = $_POST['qty'];
    $units = $_POST['units'];
    $to_address = $_POST['to'];

    $path = 'images/';
    $file = $path.$package_name.".png";
    $h = $from_address." ".$package_name." ".$quantity." ".$units." ".$to_address;
    $hash = hash('sha256',$h);
    QRcode::png($h." ".$hash, $file);

    $custom = array();

    for ($index=0; $index<const_issue_custom_fields; $index++) {
        $temp = "key".$index;
        $temp2 = "value".$index;
        $key = $_POST[$temp];
        $value = $_POST[$temp2];

        if ($key || $value){
            $fields = array($key, $value);
            $custom[$index] = $fields;
        }

    }

    $custom_fields = json_encode($custom);

    echo $custom_fields;

    $from_address = stripcslashes($from_address);
    $to_address = stripcslashes($to_address);
    $con = connect();

    $query = "Insert into issuepackage(from_address, package_name, Quantity, units, to_address, custom_fields, hash) values('$from_address', '$package_name', $quantity, $units, '$to_address', '$custom_fields', '$hash')";
    $result = mysqli_query($con, $query);
}
?>