TUNEFUSION — PHP + MYSQL (simple student project)

1. Install XAMPP (Apache, PHP, MySQL) and enable PHP PDO + cURL extensions.
2. Copy the TuneFusion folder into htdocs.
3. Open phpMyAdmin, import database/schema.sql.
4. Copy config.example.php to config.php in the project root.
5. Add your MySQL password and API credentials in config.php.
6. Visit http://localhost/TuneFusion/public/

API keys:
- YouTube Data API v3: Google Cloud Console -> enable YouTube Data API v3 -> create API key.
- Spotify: create an app at https://developer.spotify.com/dashboard and copy Client ID + Client Secret.
- JioSaavn: no generally available official public developer API; this project opens JioSaavn search rather than extracting audio.
- YouTube Music: no official public consumer search/playback API; this uses YouTube video search with "official audio" added to the query.

Playback:
- YouTube results play in the official embedded YouTube player.
- Spotify results use Spotify's official embed; full on-demand playback may require a Spotify account/Premium.
- JioSaavn opens its search page.
- YouTube's embedded player may show ads; this project does not block them.
- This is a learning/demo project. For public deployment add CSRF protection, rate limiting, HTTPS, secure session cookies and production hardening.
