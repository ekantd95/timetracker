CREATE TABLE transitions (
transition_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
event_name VARCHAR(450),
start_event BOOLEAN,
category ENUM('Work', 'WebDev', 'Chill', 'Late') NOT NULL,
length INT UNSIGNED,
time_saved DATETIME,
user_id INT UNSIGNED,
PRIMARY KEY (transition_id)
);

CREATE TABLE users (
user_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
first_name VARCHAR (20),
last_name VARCHAR (40),
email VARCHAR (60),
password VARCHAR (40),
registration_date DATETIME NOT NULL,
PRIMARY KEY (user_id)
);

CREATE TABLE categories (
category_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
category_name VARCHAR (20),
color VARCHAR (7),
active TINYINT(1),
user_id INT UNSIGNED,
time_saved DATETIME,
PRIMARY KEY (category_id)
);
