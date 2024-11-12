<?php
// Include the database configuration file to connect to the database
include("config.php");

// Drop the table if it exists to avoid duplicate errors
$drop_table = "DROP TABLE IF EXISTS c_info";
mysqli_query($myconnect, $drop_table);

// Create the customer information table (c_info)
$tbl_cinfo = "CREATE TABLE IF NOT EXISTS c_info (
    uid INT(11) NOT NULL UNIQUE,         -- Enforce unique uid
    username VARCHAR(30) NOT NULL UNIQUE, -- Enforce unique username
    first_name VARCHAR(30) NOT NULL,     -- New field for first name
    last_name VARCHAR(30) NOT NULL,      -- New field for last name
    birthdate DATE,
    gender VARCHAR(10),
    email VARCHAR(50) NOT NULL,
    phone VARCHAR(15) NOT NULL,
    hometown VARCHAR(30),
    preferred_hall VARCHAR(50),
    room_type VARCHAR(15),
    move_in_date DATE,
    password VARCHAR(255) NOT NULL,
    action TINYINT(1) DEFAULT 1,
    role VARCHAR(50) NOT NULL,
    PRIMARY KEY (uid)
)";

// Execute the query to create the c_info table
if (mysqli_query($myconnect, $tbl_cinfo)) {
    echo "<p>Customer information table created successfully</p>";
} else {
    echo "<p>Error creating customer information table: " . mysqli_error($myconnect) . "</p>";
}
?>
