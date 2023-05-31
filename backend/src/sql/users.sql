DROP TABLE IF EXISTS users;

CREATE TABLE users
  (
     id         INT PRIMARY KEY auto_increment NOT NULL,
     email      VARCHAR(255) NOT NULL,
     password   VARCHAR(100) NOT NULL,
     fullname   VARCHAR(100) NOT NULL,
     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
     updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
     UNIQUE (email)
  );  