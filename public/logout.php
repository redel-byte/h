<?php
session_start();
require_once '../index.php';

Auth::logout();

header('Location: /login.php');
exit;
