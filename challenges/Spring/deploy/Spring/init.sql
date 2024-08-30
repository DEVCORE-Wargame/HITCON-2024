CREATE DATABASE IF NOT EXISTS testdb;

USE testdb;

CREATE TABLE IF NOT EXISTS pages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    route VARCHAR(255) NOT NULL,
    name VARCHAR(255) NOT NULL
);

INSERT INTO pages (route, name) VALUES ('', 'index');
INSERT INTO pages (route, name) VALUES ('uma', 'spring');

CREATE USER IF NOT EXISTS 'devcore'@'%' IDENTIFIED BY 'dev_pass_ph';

GRANT SELECT ON testdb.pages TO 'devcore'@'%';

FLUSH PRIVILEGES;
