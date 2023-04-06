CREATE DATABASE doingsdone DEFAULT CHARACTER SET 'utf8' DEFAULT COLLATE 'utf8_general_ci';

USE doingsdone;

CREATE TABLE projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name CHAR(128) NOT NULL,
    KEY(user_id),
    KEY(name)
);

CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    project_id INT NOT NULL,
    title CHAR(255) NOT NULL,
    file CHAR(255),
    status BOOL DEFAULT 0,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    date_finish DATE
);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(128) NOT NULL UNIQUE,
    name CHAR(128) NOT NULL,
    password CHAR(64) NOT NULL,
    date_created TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE FULLTEXT INDEX t_ft ON tasks(title);

CREATE FULLTEXT INDEX p_ft ON projects(name);
