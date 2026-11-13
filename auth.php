<?php
require __DIR__.'/bootstrap.php';
$action=$_GET['action'] ?? '';
if ($action==='me') {
  if (empty($_SESSION['uid'])) out(['user'=>null]);
  $s=db()->prepare("SELECT user_id,username,email FROM users WHERE user_id=? AND status='Active'");
  $s->execute([$_SESSION['uid']]); out(['user'=>$s->fetch() ?: null]);
}
if ($action==='logout') { session_destroy(); out(['ok'=>true]); }
$d=body();
if ($action==='signup') {
  $name=trim($d['username'] ?? ''); $email=strtolower(trim($d['email'] ?? '')); $pass=$d['password'] ?? '';
  if (strlen($name)<3 || strlen($name)>50 || !filter_var($email,FILTER_VALIDATE_EMAIL) || strlen($pass)<8) out(['error'=>'Enter a valid username/email and password (8+ characters).'],400);
  try {
    $s=db()->prepare("INSERT INTO users(username,email,password_hash) VALUES(?,?,?)");
    $s->execute([$name,$email,password_hash($pass,PASSWORD_DEFAULT)]);
    $_SESSION['uid']=(int)db()->lastInsertId();
    out(['user'=>['username'=>$name,'email'=>$email]]);
  } catch (PDOException $e) { out(['error'=>'Email is already registered.'],409); }
}
if ($action==='login') {
  $s=db()->prepare("SELECT * FROM users WHERE email=? AND status='Active'");
  $s->execute([strtolower(trim($d['email'] ?? ''))]); $u=$s->fetch();
  if (!$u || !password_verify($d['password'] ?? '',$u['password_hash'])) out(['error'=>'Invalid email or password.'],401);
  session_regenerate_id(true); $_SESSION['uid']=$u['user_id'];
  out(['user'=>['username'=>$u['username'],'email'=>$u['email']]]);
}
out(['error'=>'Unknown action'],404);
