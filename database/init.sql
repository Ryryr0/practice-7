CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(225) NOT NULL,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE toys (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    category_id INT,
    price INT NOT NULL,
    description VARCHAR(255)
);

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

INSERT INTO categories (name) VALUES
('funny'),
('sad'),
('helpfull');

INSERT INTO toys (name, category_id, price, description) VALUES
('cuddly warm', 1, 200, NULL),
('little bee', NULL, 22, NULL),
('big hug', 2, 99999, NULL);

INSERT INTO admins (username, password) VALUES
('admin', '1234');

INSERT INTO users (name, password) VALUES
('user 1', '1234'),
('user 2', '1234');
