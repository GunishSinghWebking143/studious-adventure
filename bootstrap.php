<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');
$configFile = __DIR__ . '/../config.php';
if (!file_exists($configFile)) {
  http_response_code(503); echo json_encode(['error'=>'Copy config.example.php to config.php and add your API keys/database details.']); exit;
}
$config = require $configFile;
function db(): PDO {
  global $config;
  return new PDO(
    'mysql:host='.$config['db_host'].';dbname='.$config['db_name'].';charset=utf8mb4',
    $config['db_user'], $config['db_pass'],
    [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]
  );
}
function body(): array { return json_decode(file_get_contents('php://input'), true) ?: []; }
function out($data, int $status=200): void { http_response_code($status); echo json_encode($data); exit; }
function http_get_json(string $url, array $headers=[]): array {
  $ch=curl_init($url);
  curl_setopt_array($ch,[CURLOPT_RETURNTRANSFER=>true,CURLOPT_TIMEOUT=>15,CURLOPT_HTTPHEADER=>$headers]);
  $raw=curl_exec($ch); $code=curl_getinfo($ch,CURLINFO_HTTP_CODE); curl_close($ch);
  if ($raw===false || $code>=400) throw new Exception('Music provider request failed.');
  return json_decode($raw,true) ?: [];
}
