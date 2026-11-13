<!doctype html>
<html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>TuneFusion</title><link rel="stylesheet" href="style.css"></head>
<body><header><h1>♪ TuneFusion</h1><button id="account">Log in</button></header>
<main><h2>Your music, your mood.</h2><p>Search across music platforms.</p>
<div class="search"><input id="q" placeholder="Search song or artist"><select id="source">
<option value="youtube">YouTube</option><option value="youtubemusic">YouTube Music-style search</option><option value="spotify">Spotify</option><option value="jiosaavn">JioSaavn</option></select><button id="search">Search</button></div>
<p id="message"></p><section id="results"></section><section id="player"></section></main>
<div id="modal" hidden><form id="auth"><button type="button" id="close">×</button><h2 id="authTitle">Log in</h2><input id="username" placeholder="Username"><input id="email" type="email" placeholder="Email" required><input id="password" type="password" placeholder="Password (8+ characters)" required><button>Continue</button><a href="#" id="switch">Create account</a><p id="error"></p></form></div>
<script src="app.js"></script></body></html>
