CREATE TABLE posts (
                       id INT AUTO_INCREMENT PRIMARY KEY,
                       slug VARCHAR(255) NOT NULL,
                       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE post_translations (
                                   id INT AUTO_INCREMENT PRIMARY KEY,
                                   post_id INT NOT NULL,
                                   language_code VARCHAR(10) NOT NULL,
                                   title VARCHAR(255) NOT NULL,
                                   content TEXT NOT NULL,
                                   FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE
);

INSERT INTO posts (slug) VALUES ('hello-world');
INSERT INTO post_translations (post_id, language_code, title, content)
VALUES (1, 'en', 'Hello World', 'This is a post in English.');
INSERT INTO post_translations (post_id, language_code, title, content)
VALUES (1, 'ru', 'Привет Мир', 'Это запись на русском.');