<?php

if($_SERVER["REQUEST_METHOD"]=="POST"){

    $username= $_POST['username'];
   $password= $_POST['password'];

   $host= "localhost";
   $dbusername="root";
    $dbpassword= "";
    $dbname= "beatbunk";

  $conn = new mysqli($host,$dbusername,$password,$dbname);


  if($conn->connect_error) {

    die("Connection failed". $conn->connect_error);

}

$query = "SELECT * FROM login WHERE username='$username' AND passoword='password' " ;

$result = $conn->query($query) ;

if($result->num_rows ==1) { 
    exit();
}
else{
    exit();
}


}

  