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
$faker = Faker\Factory::create('en_PH');


for ($i = 0; $i < 100; $i++) {

    $lastName = addslashes($faker->lastName);
    $firstName = addslashes($faker->firstName);

    $domain = $faker->freeEmailDomain;
    $randomNumber = $conn->real_escape_string($faker->numberBetween(0, 999));

    $lastNameSymbolsRemover = preg_replace("/[^a-zA-Z0-9\.]/", "", $lastName); //prep para sa email, duh
    $firstNameSymbolsRemover = preg_replace("/[^a-zA-Z0-9\.]/", "", $firstName); //tanggal li'bug'

    // $email = $conn->real_escape_string($faker->email); //ayaw ko ng random email
    $email = $conn->real_escape_string(strtolower($firstNameSymbolsRemover) . '.' . strtolower($lastNameSymbolsRemover) . $randomNumber . '@' . $domain);

    // $userName = $conn->real_escape_string($faker->userName); //use mo 'to  to create very random username, ayaw ko nito
    $userName = $conn->real_escape_string($firstNameSymbolsRemover . $lastNameSymbolsRemover . $randomNumber); //based sa name and random blah blah
    $password = $conn->real_escape_string($faker->password);

    $sql = "INSERT INTO useraccount(email, 
                                    lastName, 
                                    firstName, 
                                    userName, 
                                    password) 

        values( '$email', 
                '$lastName', 
                '$firstName', 
                '$userName', 
                '$password')";

    if (!mysqli_query($conn, $sql)) {
        echo "Error: " . mysqli_error($conn);
    }
}