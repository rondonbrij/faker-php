<?php

//Agum x Valmoria

// include "db-connect.php"; //ibang file sana pero single file lang pala upload

$servername = "127.0.0.1"; //replace with your own blah blah
$username = "root"; // this too?
$password = "root"; // as well
$dbname = "faker"; // depends on u baby

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";

require('vendor/autoload.php');
$faker = Faker\Factory::create();

$result = $conn->query("SELECT id FROM useraccount");
$userIds = array();
while ($row = $result->fetch_assoc()) {
    $userIds[] = $row['id'];
}

for ($i = 0; $i < 20; $i++) {
    $creditCardType = $conn->real_escape_string($faker->creditCardType);
    $creditCardNumber = $conn->real_escape_string($faker->unique()->creditCardNumber);
    $creditCardExpirationDate = $conn->real_escape_string($faker->creditCardExpirationDateString);
    $userIdNumber = $userIds[array_rand($userIds)]; //base sa existing na ids sa kabilang table 
                                                    //useraccounts? 
                                                    //ata -
                                                    //ewan joke lang 
                                                    //ihhh

                                                    //note to our most beautiful teacher
                                                    //ayaw ko 1-100 ma'am, pag more than 100 yung user account paano na? :<
                                                    //paano na po yung iba T_T

    $sql = "INSERT INTO carddetail(
        creditCardType, 
        creditCardNumber, 
        creditCardExpirationDate, 
        userIdNumber) 
            values(
                 '$creditCardType', 
                 '$creditCardNumber', 
                 '$creditCardExpirationDate', 
                 $userIdNumber)";

    if (!mysqli_query($conn, $sql)) {
        echo "Error: " . mysqli_error($conn);
    }
}