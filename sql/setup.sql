DROP DATABASE IF EXISTS master_mind_contacts_app;

CREATE DATABASE master_mind_contacts_app;

USE master_mind_contacts_app;

CREATE TABLE users(
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255),
  email VARCHAR(255) UNIQUE,
  password VARCHAR(255)
);

CREATE TABLE contacts(
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255),
  phone_number VARCHAR(255),
  user_id INT NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- INSERT INTO contacts (name, phone_number) 
--               VALUES ('Emmanuel', '87871820'),
--                      ('Victoria', '88032803');