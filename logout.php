<?php
session_start();
session_unset();
session_destroy();
header("Location: SE database interface.html");
exit;
?>