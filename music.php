<?php
require __DIR__.'/bootstrap.php';
$q=trim($_GET['q'] ?? ''); $source=strtolower($_GET['source'] ?? 'youtube');
if (strlen($q)<2) out(['error'=>'Enter at least 2 characters.'],400);
try {
  if ($source==='youtube' || $source==='youtubemusic') {
    $query=$source==='youtubemusic' ? $q.' official audio' : $q;
    $url='https://www.googleapis.com/youtube/v3/search?'.http_build_query([
      'part'=>'snippet','type'=>'video','videoEmbeddable'=>'true','maxResults'=>10,
      'q'=>$query,'key'=>$config['youtube_api_key']
    ]);
    $data=http_get_json($url); $items=[];
    foreach (($data['items'] ?? []) as $v) {
      $id=$v['id']['videoId'] ?? ''; if (!$id) continue;
      $sn=$v['snippet'];
      $items[]=['title'=>$sn['title'],'artist'=>$sn['channelTitle'],'id'=>$id,
        'image'=>$sn['thumbnails']['medium']['url'] ?? '',
        'embed'=>'https://www.youtube-nocookie.com/embed/'.$id,
        'url'=>'https://www.youtube.com/watch?v='.$id,'source'=>$source];
    }
    out(['items'=>$items]);
  }
  if ($source==='spotify') {
    $token=base64_encode($config['spotify_client_id'].':'.$config['spotify_client_secret']);
    $ch=curl_init('https://accounts.spotify.com/api/token');
    curl_setopt_array($ch,[CURLOPT_RETURNTRANSFER=>true,CURLOPT_POST=>true,
      CURLOPT_POSTFIELDS=>'grant_type=client_credentials',CURLOPT_HTTPHEADER=>['Authorization: Basic '.$token,'Content-Type: application/x-www-form-urlencoded']]);
    $raw=curl_exec($ch); curl_close($ch); $auth=json_decode($raw,true);
    if (empty($auth['access_token'])) throw new Exception('Spotify credentials are missing or invalid.');
    $data=http_get_json('https://api.spotify.com/v1/search?'.http_build_query(['q'=>$q,'type'=>'track','limit'=>10]),
      ['Authorization: Bearer '.$auth['access_token']]);
    $items=[];
    foreach (($data['tracks']['items'] ?? []) as $t) $items[]=[
      'title'=>$t['name'],'artist'=>implode(', ',array_column($t['artists'],'name')),
      'id'=>$t['id'],'image'=>$t['album']['images'][1]['url'] ?? '',
      'embed'=>'https://open.spotify.com/embed/track/'.$t['id'],
      'url'=>$t['external_urls']['spotify'],'source'=>'spotify'];
    out(['items'=>$items]);
  }
  if ($source==='jiosaavn') {
    // JioSaavn does not provide a generally available official public developer API.
    // Provide a direct search link rather than scraping or extracting audio streams.
    out(['items'=>[['title'=>'Open JioSaavn search','artist'=>'Search JioSaavn for: '.$q,
      'id'=>'','image'=>'','embed'=>'','url'=>'https://www.jiosaavn.com/search/'.$q,
      'source'=>'jiosaavn','external'=>true]]]);
  }
  out(['error'=>'Unknown music source.'],400);
} catch (Throwable $e) { out(['error'=>$e->getMessage()],502); }
