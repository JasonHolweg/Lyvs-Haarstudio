<?php
session_start();

require __DIR__ . '/admin-config.php';

function esc($value)
{
  return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function admin_is_authenticated()
{
  return !empty($_SESSION['lyvs_admin_auth']);
}

function admin_logout()
{
  if (isset($_POST['logout'])) {
    $_SESSION['lyvs_admin_auth'] = false;
  }
}

function admin_handle_login(&$error_message)
{
  if (!isset($_POST['access_code'])) {
    return;
  }

  $access_code = trim((string) $_POST['access_code']);
  if ($access_code === '') {
    $error_message = 'Bitte Zugangscode eingeben.';
    return;
  }

  global $admin_access_code;

  if (hash_equals($admin_access_code, $access_code)) {
    $_SESSION['lyvs_admin_auth'] = true;
  } else {
    $error_message = 'Zugangscode ist nicht korrekt.';
  }
}
