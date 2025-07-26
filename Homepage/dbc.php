<?php
$connection = mysqli_connect("localhost", "root", "geneswim2025", "geneswim");
if (!$connection)
 die("Connection failed: " . mysqli_connect_error());
else
echo "Connected successfully";

?>