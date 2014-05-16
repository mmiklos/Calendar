CREATE DATABASE game;
use game;

GRANT ALL PRIVILEGES ON game.* 
TO 'gamer'@'localhost' 
IDENTIFIED BY 'randompassword';

CREATE TABLE users( 
	id INT(11) NOT NULL AUTO_INCREMENT,
	username VARCHAR(50) NOT NULL,
	password VARCHAR(70) NOT NULL, 
	name VARCHAR(60) NOT NULL, 
	PRIMARY KEY(id)
);


CREATE TABLE topgames (
	id INT( 12 ) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	title VARCHAR(200) NOT NULL,
	genre VARCHAR(20) NOT NULL,
	tags TEXT
);

CREATE TABLE images (
	id INT( 12 ) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	topgames_id INT( 12 ) NOT NULL,
	filename VARCHAR(200) NOT NULL,
	type VARCHAR(2083) NOT NULL,
	size VARCHAR(20) NOT NULL,
	alt TEXT,
	INDEX(topgames_id)
);

CREATE TABLE calendar (
	id INT ( 12 ) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	date DATETIME NOT NULL,
	activity_type VARCHAR(100) NOT NULL,
	who VARCHAR(100) NOT NULL,
	what VARCHAR(100) NOT NULL,
	timezone VARCHAR(10) NOT NULL,
	url VARCHAR(100),
	address VARCHAR(200),
	activity_list VARCHAR(500),
	optional_text VARCHAR(1000)
);

CREATE TABLE geoip_country (
	id INT( 12 ) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	start_ipv6_address VARCHAR( 15 ) NOT NULL,
	end_ipv6_address VARCHAR( 15 ) NOT NULL,
	netmask_1 INT( 40 ) UNSIGNED NOT NULL,
	netmask_2 INT( 40 ) UNSIGNED NOT NULL,
	country_code VARCHAR( 2 ) NOT NULL,
	country_name VARCHAR( 50 ) NOT NULL
);
CREATE TABLE country_ip (
	id INT( 12 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	start_ipv4_address VARCHAR( 15 ) NOT NULL,
	end_ipv4_address VARCHAR( 15 ) NOT NULL,
	netmask_1 INT( 12 ) UNSIGNED NOT NULL,
	netmask_2 INT( 12 ) UNSIGNED NOT NULL,
	country_code VARCHAR( 2 ) NOT NULL,
	country_name VARCHAR( 50 ) NOT NULL,
	INDEX(start_ipv4_address, end_ipv4_address)
);
INSERT INTO topgames (title, img, genre, tags)
VALUES ('Age of Empires II: The Age of Kings', 'img/game_thumbs/Age_of_empires_II_the_age_of_kings.png', 'RTS', 'computer 1999');