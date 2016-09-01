CREATE TABLE IF NOT EXISTS users(
role VARCHAR(20) DEFAULT "customer",
first_name VARCHAR(20) NOT NULL,
last_name VARCHAR(20) NOT NULL,
email VARCHAR(50) NOT NULL PRIMARY KEY,
password VARCHAR(10) NOT NULL,
address VARCHAR(100) NOT NULL,
city VARCHAR(30) NOT NULL,
zipcode INTEGER(5) NOT NULL,
state VARCHAR(2) NOT NULL,
dateCreated TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS items(
prod_id INTEGER(5) NOT NULL AUTO_INCREMENT PRIMARY KEY,
price DECIMAL(10,2) NOT NULL,
name VARCHAR(30) NOT NULL,
quantity INTEGER(5) NOT NULL,
promo_discount INTEGER(3) NOT NULL DEFAULT 0,
dateCreated TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS orders(
status VARCHAR(30) DEFAULT "Pending",
total DECIMAL(10,2) NOT NULL,
order_num INTEGER(5) NOT NULL AUTO_INCREMENT PRIMARY KEY,
date_time DATE DEFAULT NULL,
user_email VARCHAR(50) NOT NULL,
FOREIGN KEY(user_email) REFERENCES users(email)
);

CREATE TABLE IF NOT EXISTS order_details(
   id INTEGER(5) AUTO_INCREMENT NOT NULL PRIMARY KEY,
   prod_id INTEGER(5) NOT NULL,
   buying_price DECIMAL(10,2) NOT NULL,
   order_num INTEGER(5) NOT NULL,
   quantity INTEGER(5) NOT NULL,
   FOREIGN KEY(order_num) REFERENCES orders(order_num),
   FOREIGN KEY(prod_id) REFERENCES items(prod_id)
);


INSERT IGNORE INTO users (role, first_name, last_name, email, password, address, city, zipcode, state)
VALUES("manager", "Store", "Manager", "manager@gmail.com", "password", "123 Fake Street", "Lexington", 40508, "KY"),
("staff", "Store", "Staff", "staff@gmail.com", "password", "123 Fake Street", "Lexington", 40508, "KY"),
("customer", "Store", "Customer", "customer@gmail.com", "password", "123 Fake Street", "Lexington", "40508", "KY");

INSERT IGNORE INTO orders(user_email) VALUES ("manager@gmail.com"), ("staff@gmail.com"), ("customer@gmail.com");
