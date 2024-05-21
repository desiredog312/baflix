<?php
session_start();
session_destroy();
echo json_encode(['logged_out' => true]);
?>
