-- TuneFusion DBMS schema (MySQL 8.0+)
CREATE DATABASE IF NOT EXISTS tunefusion
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE tunefusion;

CREATE TABLE IF NOT EXISTS users (
  user_id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  email VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  status ENUM('Active', 'Inactive') NOT NULL DEFAULT 'Active'
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS songs (
  song_id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(150) NOT NULL,
  artist VARCHAR(100) NOT NULL,
  genre ENUM('Hindi', 'Punjabi') NOT NULL,
  duration VARCHAR(10),
  cover_image VARCHAR(255),
  audio_url VARCHAR(255),
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS user_history (
  history_id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  song_id INT NOT NULL,
  played_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_history_user FOREIGN KEY (user_id)
    REFERENCES users(user_id) ON DELETE CASCADE,
  CONSTRAINT fk_history_song FOREIGN KEY (song_id)
    REFERENCES songs(song_id) ON DELETE CASCADE,
  INDEX idx_history_user_played (user_id, played_at)
) ENGINE=InnoDB;

-- Optional sample catalogue. Replace media paths with audio you own or are licensed to use.
INSERT INTO songs (title, artist, genre, duration, cover_image, audio_url) VALUES
('Kesariya', 'Arijit Singh', 'Hindi', '4:28', 'assets/kesariya.jpg', 'assets/audio/kesariya.mp3'),
('Heeriye', 'Jasleen Royal, Arijit Singh', 'Hindi', '3:19', 'assets/heeriye.jpg', 'assets/audio/heeriye.mp3'),
('Excuses', 'AP Dhillon', 'Punjabi', '2:56', 'assets/excuses.jpg', 'assets/audio/excuses.mp3'),
('With You', 'AP Dhillon', 'Punjabi', '2:34', 'assets/with-you.jpg', 'assets/audio/with-you.mp3');

-- Example queries:
-- SELECT song_id, title, artist, genre, duration FROM songs ORDER BY title;
-- SELECT * FROM songs WHERE genre = 'Punjabi';
-- INSERT INTO user_history (user_id, song_id) VALUES (1, 1);
-- SELECT s.title, s.artist, h.played_at
-- FROM user_history h JOIN songs s ON s.song_id = h.song_id
-- WHERE h.user_id = 1 ORDER BY h.played_at DESC;
