<?php

require('User.php');

$users = new User();
$users->createTable(); // use to create the table
$users->populateTable(); // use this to populate the table with random data
