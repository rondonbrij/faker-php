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

require 'vendor/autoload.php';

$faker = Faker\Factory::create('en_PH');

$range = 200;

for ($i = 0; $i < $range; $i++) {

    //office
    $companyName = $conn->real_escape_string($faker->company);
    $contactnum = $conn->real_escape_string($faker->unique()->phoneNumber);
    $email = $conn->real_escape_string($faker->companyEmail);
    $address = $conn->real_escape_string($faker->streetName); //mga out of this country ang street name XD
    $city = $conn->real_escape_string($faker->city);
    $country = $conn->real_escape_string($faker->country);
    // $country = $conn->real_escape_string('Pilippines'); //syempre nasa Pinas na tayo e, di na need. localize na

    // $postal = $conn->real_escape_string($faker->postcode); //gamitin kaso random lang, kasi nga fake iww
    $postal = $faker->numberBetween($min = 400, $max = 9814); //base sa zipcode.com yan daw range ng sa pinas, but may tatlong digits siyang input instead of 4 (0111)


    $office_sql = "INSERT INTO office(name, contactnum, email, address, city, country, postal) 
    values('$companyName', '$contactnum', '$email', '$address', '$city', '$country', '$postal')";

    if (!mysqli_query($conn, $office_sql)) {
        echo "Error: " . mysqli_error($conn);
    }
}

/////////////////////////////



$result = $conn->query("SELECT id FROM office");
$office_userIds = array();
while ($row = $result->fetch_assoc()) {
    $office_userIds[] = $row['id'];
}

for ($i = 0; $i < $range; $i++) {

    //employee
    $lastName = $conn->real_escape_string($faker->lastName);
    $firstName = $conn->real_escape_string($faker->firstName);
    $office_id = $office_userIds[array_rand($office_userIds)];
    $address = $conn->real_escape_string($faker->optional()->address);

    $employee_sql = "INSERT INTO employee(lastName, firstName, office_id, address) 
        values('$lastName', '$firstName', $office_id, '$address')";

    if (!mysqli_query($conn, $employee_sql)) {
        echo "Error: " . mysqli_error($conn);
    }
}

///////////////////////////////////////////

$result_1 = $conn->query("SELECT id FROM employee");
$employee_userIds = array();
while ($row = $result_1->fetch_assoc()) {
    $employee_userIds[] = $row['id'];
}

for ($i = 0; $i < $range; $i++) {

    //TRANSaction
    $employee_id = $employee_userIds[array_rand($employee_userIds)];
    $startDate = new DateTime('2023-01-01'); //kasi gusto ko
    $endDate = new DateTime();
    $datelog = $faker->dateTimeBetween($startDate, $endDate)->format('Y-m-d H:i:s');
    $datelog = $conn->real_escape_string($datelog);
    $action = $conn->real_escape_string($faker->randomElement($array = array('IN', 'OUT', 'COMPLETE')));
    $remarks = $conn->real_escape_string($faker->optional()->randomElement($array = array('Signed', 'For approval'))); //naka optional, yung iba sa example kasi walang laman, duh
    $documentcode = $faker->numberBetween(100, 101); // dalawa lang yung sa office e, bruh

    $transaction_sql = "INSERT INTO transaction(employee_id, office_id, datelog, action, remarks, documentcode) 
        values($employee_id, $office_id, '$datelog', '$action', '$remarks', $documentcode)";

    if (!mysqli_query($conn, $transaction_sql)) {
        echo "Error: " . mysqli_error($conn);
    }
}