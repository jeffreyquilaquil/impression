<?php
require_once ( 'inc_conn.php' );
session_destroy();
redirect('index.php');
?>