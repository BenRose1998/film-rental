DROP DATABASE IF EXISTS film_rental;

CREATE DATABASE IF NOT EXISTS film_rental;

USE film_rental;

CREATE TABLE IF NOT EXISTS rating(
  rating_id CHAR(6) PRIMARY KEY,
  rating_name VARCHAR(3) NOT NULL,
  CONSTRAINT check_rating_name CHECK(rating_name IN ('U', 'PG', '12A', '12', '15', '18', 'R18'))
);

CREATE TABLE IF NOT EXISTS category(
  category_id CHAR(6) PRIMARY KEY,
  category_name VARCHAR(30) NOT NULL
);

CREATE TABLE IF NOT EXISTS staff(
  staff_id CHAR(6) PRIMARY KEY,
  staff_first_name VARCHAR(20) NOT NULL,
  staff_last_name VARCHAR(20) NOT NULL,
  staff_street_number VARCHAR(30) NOT NULL,
  staff_street VARCHAR(20) NOT NULL,
  staff_town VARCHAR(20) NOT NULL,
  staff_county VARCHAR(20),
  staff_postcode VARCHAR(8) NOT NULL,
  staff_email VARCHAR(30) NOT NULL,
  staff_mobile VARCHAR(11),
  staff_start_date DATE NOT NULL,
  staff_position VARCHAR(20),
  staff_password VARCHAR(255),
  CONSTRAINT check_staff_position CHECK(staff_position IN ('Sales Assistant', 'Assistant Manager', 'Manager'))
);

CREATE TABLE IF NOT EXISTS film(
  film_id CHAR(5) PRIMARY KEY,
  category_id CHAR(6) NOT NULL,
  rating_id CHAR(6) NOT NULL,
  film_title VARCHAR(30) NOT NULL,
  film_rental_price_per_day DECIMAL(3,2) NOT NULL,
  CONSTRAINT FK_film_category_id FOREIGN KEY (category_id) REFERENCES category(category_id) ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT FK_film_rating_id FOREIGN KEY (rating_id) REFERENCES rating(rating_id) ON UPDATE CASCADE ON DELETE RESTRICT
);

CREATE TABLE IF NOT EXISTS supplier(
  supplier_id CHAR(6) PRIMARY KEY,
  supplier_name VARCHAR(20),
  supplier_street_number VARCHAR(30) NOT NULL,
  supplier_street VARCHAR(20) NOT NULL,
  supplier_town VARCHAR(20) NOT NULL,
  supplier_county VARCHAR(20),
  supplier_postcode VARCHAR(8) NOT NULL
);

CREATE TABLE IF NOT EXISTS stock(
  stock_id CHAR(6) PRIMARY KEY,
  film_id CHAR(5) NOT NULL,
  supplier_id CHAR(6) NOT NULL,
  CONSTRAINT FK_stock_film_id FOREIGN KEY (film_id) REFERENCES film(film_id) ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT FK_stock_supplier_id FOREIGN KEY (supplier_id) REFERENCES supplier(supplier_id) ON UPDATE CASCADE ON DELETE RESTRICT
);

CREATE TABLE IF NOT EXISTS customer(
  customer_id CHAR(6) PRIMARY KEY,
  customer_title VARCHAR(4),
  customer_first_name VARCHAR(20) NOT NULL,
  customer_last_name VARCHAR(20) NOT NULL,
  customer_street_number VARCHAR(30) NOT NULL,
  customer_street VARCHAR(20) NOT NULL,
  customer_town VARCHAR(20) NOT NULL,
  customer_county VARCHAR(20),
  customer_postcode VARCHAR(8) NOT NULL,
  customer_email VARCHAR(30) NOT NULL,
  customer_mobile VARCHAR(11),
  CONSTRAINT check_customer_title CHECK(customer_title IN ('Mr', 'Mrs', 'Miss', 'Ms', 'Sir', 'Dr'))
);

CREATE TABLE IF NOT EXISTS rental(
  rental_id CHAR(5) PRIMARY KEY,
  stock_id CHAR(6) NOT NULL,
  customer_id CHAR(6) NOT NULL,
  staff_id CHAR(6) NOT NULL,
  rental_date DATE NOT NULL,
  rental_return_date DATE NOT NULL,
  CONSTRAINT FK_rental_stock_id FOREIGN KEY (stock_id) REFERENCES stock(stock_id) ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT FK_rental_customer_id FOREIGN KEY (customer_id) REFERENCES customer(customer_id) ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT FK_rental_staff_id FOREIGN KEY (staff_id) REFERENCES staff(staff_id) ON UPDATE CASCADE ON DELETE RESTRICT
);

CREATE TABLE IF NOT EXISTS contact(
  contact_id CHAR(6) PRIMARY KEY,
  contact_first_name VARCHAR(20) NOT NULL,
  contact_last_name VARCHAR(20) NOT NULL,
  contact_street_number VARCHAR(30) NOT NULL,
  contact_street VARCHAR(20) NOT NULL,
  contact_town VARCHAR(20) NOT NULL,
  contact_county VARCHAR(20),
  contact_postcode VARCHAR(8) NOT NULL,
  contact_email VARCHAR(30) NOT NULL,
  contact_mobile VARCHAR(11)
);

CREATE TABLE IF NOT EXISTS supplier_contact(
  contact_id CHAR(6) NOT NULL,
  supplier_id CHAR(6) NOT NULL,
  CONSTRAINT FK_supplier_contact_contact_id FOREIGN KEY (contact_id) REFERENCES contact(contact_id) ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT FK_supplier_contact_supplier_id FOREIGN KEY (supplier_id) REFERENCES supplier(supplier_id) ON UPDATE CASCADE ON DELETE RESTRICT
);

-- Views
CREATE VIEW IF NOT EXISTS view_all_contacts AS
SELECT 'Customer' AS 'contact_type', CONCAT_WS(' ', customer_first_name, customer_last_name) AS 'name', CONCAT_WS(', ', customer_street_number, customer_street, customer_town, customer_county, customer_postcode) AS 'address', customer_email AS 'email', customer_mobile AS 'mobile' FROM customer
UNION
SELECT 'Staff' AS 'contact_type', CONCAT_WS(' ', staff_first_name, staff_last_name) AS 'name', CONCAT_WS(', ', staff_street_number, staff_street, staff_town, staff_county, staff_postcode), staff_email, staff_mobile FROM staff
UNION
SELECT 'Contact' AS 'contact_type', CONCAT_WS(' ', contact_first_name, contact_last_name) AS 'name', CONCAT_WS(', ', contact_street_number, contact_street, contact_town, contact_county, contact_postcode), contact_email, contact_mobile FROM contact;

-- Stored Procedures
DROP PROCEDURE IF EXISTS show_supplier_details;
DELIMITER //
CREATE PROCEDURE show_supplier_details()
BEGIN
SELECT supplier_name AS 'Name', CONCAT_WS(', ', supplier_street_number, supplier_street, supplier_town, supplier_county, supplier_postcode) AS 'Address' FROM supplier;
END //
DELIMITER ;

-- Indexes
CREATE INDEX index_film ON film(film_id);
CREATE INDEX index_category ON category(category_id);
CREATE INDEX index_rating ON rating(rating_id);
CREATE INDEX index_stock ON stock(stock_id);
CREATE INDEX index_customer ON customer(customer_id);
CREATE INDEX index_staff ON staff(staff_id);
CREATE INDEX index_supplier ON supplier(supplier_id);
CREATE INDEX index_rental ON rental(rental_id, stock_id);

-- Insert Data
INSERT INTO rating VALUES ('RT1001', 'U');
INSERT INTO rating VALUES ('RT1002', 'PG');
INSERT INTO rating VALUES ('RT1003', '12A');
INSERT INTO rating VALUES ('RT1004', '12');
INSERT INTO rating VALUES ('RT1005', '15');
INSERT INTO rating VALUES ('RT1006', '18');
INSERT INTO rating VALUES ('RT1007', 'R18');

INSERT INTO category VALUES ('CA1001', 'Drama');
INSERT INTO category VALUES ('CA1002', 'Action');
INSERT INTO category VALUES ('CA1003', 'Thriller');
INSERT INTO category VALUES ('CA1004', 'Adventure');
INSERT INTO category VALUES ('CA1005', 'Horror');

INSERT INTO film VALUES ('F1001', 'CA1001', 'RT1005', 'The Shawshank Redemption', '0.50');
INSERT INTO film VALUES ('F1002', 'CA1002', 'RT1002', 'The Dark Knight', '0.75');
INSERT INTO film VALUES ('F1003', 'CA1001', 'RT1006', 'Pulp Fiction', '0.50');
INSERT INTO film VALUES ('F1004', 'CA1001', 'RT1002', 'Forrest Gump', '0.50');
INSERT INTO film VALUES ('F1005', 'CA1003', 'RT1006', 'Joker', '1.00');
INSERT INTO film VALUES ('F1006', 'CA1001', 'RT1006', 'The Wolf of Wall Street', '0.75');

INSERT INTO staff VALUES ('SF1001', 'Dom', 'Gepson', 'Green', '7173 Charing Cross Place', 'Washington', 'District of Columbia', '20244', 'dom@gepson.com', '2024457071', '2019-09-12', 'Sales Assistant', '$2y$10$TzgpK9yZyGXmYkW2QuYzM.MqD0QoDdWuAxjoM14VG5J7j8lwDQ7mG');
INSERT INTO staff VALUES ('SF1002', 'Deb', 'Goodley', 'Dakota', '2 Barnett Street', 'Grand Rapids', 'Michigan', '49560', 'dgoodley1@epa.gov', '6168861530', '2018-06-22', 'Manager', '$2y$10$TzgpK9yZyGXmYkW2QuYzM.MqD0QoDdWuAxjoM14VG5J7j8lwDQ7mG');
INSERT INTO staff VALUES ('SF1003', 'Zacherie', 'Yates', 'Sycamore', '05 Crest Line Terrace', 'Charlotte', 'North Carolina', '28247', 'zyates2@dropbox.com', '7047046743', '2019-01-04', 'Sales Assistant', '$2y$10$TzgpK9yZyGXmYkW2QuYzM.MqD0QoDdWuAxjoM14VG5J7j8lwDQ7mG');
INSERT INTO staff VALUES ('SF1004', 'Hew', 'Mathelon', 'Sundown', '89 Bluejay Street', 'Dallas', 'Texas', '75379', 'hmathelon3@yolasite.com', '2149503842', '2019-02-02', 'Sales Assistant', '$2y$10$TzgpK9yZyGXmYkW2QuYzM.MqD0QoDdWuAxjoM14VG5J7j8lwDQ7mG');
INSERT INTO staff VALUES ('SF1005', 'Gabriele', 'Heatherington', 'Iowa', '97268 Grim Hill', 'Tucson', 'Arizona', '85705', 'gheatherington4@phoca.cz', '5205801735', '2019-07-02', 'Sales Assistant', '$2y$10$TzgpK9yZyGXmYkW2QuYzM.MqD0QoDdWuAxjoM14VG5J7j8lwDQ7mG');
INSERT INTO staff VALUES ('SF1006', 'Denna', 'Aire', 'Grasskamp', '417 Artisan Way', 'Charlotte', 'North Carolina', '28210', 'daire5@newyorker.com', '7046560243', '2017-09-13', 'Assistant Manager', '$2y$10$TzgpK9yZyGXmYkW2QuYzM.MqD0QoDdWuAxjoM14VG5J7j8lwDQ7mG');
INSERT INTO staff VALUES ('SF1007', 'Gregg', 'Gooley', 'Brentwood', '408 Almo Street', 'Columbus', 'Georgia', '31904', 'ggooley6@behance.net', '7065881099', '2019-08-07', 'Assistant Manager', '$2y$10$TzgpK9yZyGXmYkW2QuYzM.MqD0QoDdWuAxjoM14VG5J7j8lwDQ7mG');
INSERT INTO staff VALUES ('SF1008', 'Paddy', 'Collar', 'Scott', '181 Hagan Circle', 'Roanoke', 'Virginia', '24024', 'pcollar7@si.edu', '5405178101', '2018-12-25', 'Sales Assistant', '$2y$10$TzgpK9yZyGXmYkW2QuYzM.MqD0QoDdWuAxjoM14VG5J7j8lwDQ7mG');
INSERT INTO staff VALUES ('SF1009', 'John', 'Smith', 'Lakeland', '0 Comanche Avenue', 'Buffalo', 'New York', '14269', 'admin@admin.com', '7169932459', '2019-10-13', 'Manager', '$2y$10$TzgpK9yZyGXmYkW2QuYzM.MqD0QoDdWuAxjoM14VG5J7j8lwDQ7mG');
INSERT INTO staff VALUES ('SF1010', 'Smith', 'Liptrot', 'Blaine', '5 Cascade Park', 'Odessa', 'Texas', '79769', 'sliptrot9@nymag.com', '4326292488', '2019-05-02', 'Assistant Manager', '$2y$10$TzgpK9yZyGXmYkW2QuYzM.MqD0QoDdWuAxjoM14VG5J7j8lwDQ7mG');

INSERT INTO contact VALUES ('CT1001', 'Adler', 'Alesin', 'Hanson', '5 Russell Road', 'Aurora', 'Colorado', '80015', 'aalesin0@so-net.ne.jp', '3032428539');
INSERT INTO contact VALUES ('CT1002', 'Seth', 'Crippin', 'Sunfield', '88 Crownhardt Road', 'New Haven', 'Connecticut', '06520', 'scrippin1@economist.com', '2035995315');
INSERT INTO contact VALUES ('CT1003', 'Gillian', 'Akaster', 'Tony', '02 Prairieview Alley', 'Norfolk', 'Virginia', '23551', 'gakaster2@squarespace.com', '7577160970');
INSERT INTO contact VALUES ('CT1004', 'Gilligan', 'Bourdon', 'Fulton', '04334 Everett Alley', 'Springfield', 'Massachusetts', '01114', 'gbourdon3@aboutads.info', '4138275082');
INSERT INTO contact VALUES ('CT1005', 'Dall', 'Phifer', 'Maywood', '0 Crowley Alley', 'New York City', 'New York', '10275', 'dphifer4@ameblo.jp', '2127264931');
INSERT INTO contact VALUES ('CT1006', 'Camellia', 'Patton', 'Utah', '00 Wayridge Street', 'Nashville', 'Tennessee', '37250', 'cpatton5@hud.gov', '6154956281');
INSERT INTO contact VALUES ('CT1007', 'Licha', 'McKintosh', 'Colorado', '584 Laurel Terrace', 'San Antonio', 'Texas', '78205', 'lmckintosh6@digg.com', '2102201242');
INSERT INTO contact VALUES ('CT1008', 'Stormi', 'McFadzean', 'Ronald Regan', '90113 Dayton Hill', 'New York City', 'New York', '10090', 'smcfadzean7@squarespace.com', '2129314853');
INSERT INTO contact VALUES ('CT1009', 'Amalie', 'Skullet', 'Thompson', '53 Kingsford Hill', 'Washington', 'District of Columbia', '20546', 'askullet8@soup.io', '2027439845');
INSERT INTO contact VALUES ('CT1010', 'Nessi', 'Matthews', 'Blue Bill Park', '06 Katie Hill', 'Atlanta', 'Georgia', '30358', 'nmatthews9@sun.com', '4049681181');

INSERT INTO customer VALUES ('CS1001', 'Rev', 'Aidan', 'Gladwin', 'Beilfuss', '86550 Crowley Terrace', 'Tuscaloosa', 'Alabama', '35405', 'agladwin0@wp.com', '2051106970');
INSERT INTO customer VALUES ('CS1002', 'Dr', 'Izabel', 'Gussin', 'Red Cloud', '602 Calypso Center', 'Sandy', 'Utah', '84093', 'igussin1@drupal.org', '8011681958');
INSERT INTO customer VALUES ('CS1003', 'Ms', 'Willdon', 'Baldassi', 'Cambridge', '93287 Morning Park', 'Des Moines', 'Iowa', '50347', 'wbaldassi2@xrea.com', '5158849015');
INSERT INTO customer VALUES ('CS1004', 'Mr', 'Jennie', 'Grooby', 'Ludington', '38862 Donald Alley', 'Bridgeport', 'Connecticut', '06673', 'jgrooby3@vimeo.com', '2036607838');
INSERT INTO customer VALUES ('CS1005', 'Rev', 'Melisse', 'Skeemor', '4th', '22089 Amoth Center', 'Tucson', 'Arizona', '85725', 'mskeemor4@wikispaces.com', '5205437209');
INSERT INTO customer VALUES ('CS1006', 'Rev', 'Almire', 'Scarrott', 'Fair Oaks', '18 Meadow Vale Avenue', 'Springfield', 'Massachusetts', '01105', 'ascarrott5@dagondesign.com', '4138521526');
INSERT INTO customer VALUES ('CS1007', 'Ms', 'Myrtle', 'Foard', 'Carioca', '9741 Homewood Road', 'Stamford', 'Connecticut', '06912', 'mfoard6@hud.gov', '2035369832');
INSERT INTO customer VALUES ('CS1008', 'Ms', 'Dominic', 'Bungey', 'Miller', '56 Rutledge Plaza', 'Madison', 'Wisconsin', '53716', 'dbungey7@cisco.com', '6082754530');
INSERT INTO customer VALUES ('CS1009', 'Dr', 'Clemmy', 'Gaynsford', 'Merry', '204 Troy Place', 'Washington', 'District of Columbia', '20226', 'cgaynsford8@icq.com', '2028565879');
INSERT INTO customer VALUES ('CS1010', 'Rev', 'Gav', 'Feldbrin', 'Clyde Gallagher', '6548 Sullivan Parkway', 'Louisville', 'Kentucky', '40293', 'gfeldbrin9@live.com', '5029038791');

INSERT INTO supplier VALUES ('SP1001', 'Sandbeck', 'Sandbeck Industrial Estate', 'Sandbeck Way', 'Wetherby', 'West Yorkshire', 'LS22 7DN');
INSERT INTO supplier VALUES ('SP1002', 'Pavilion', 'The Pavilion', 'St. Stephens Road', 'Norwich', 'East Anglia', 'NR1 3SP');
INSERT INTO supplier VALUES ('SP1003', 'Reldeen', 'Reldeen House', 'Wyke Way', 'Melton West Business Park', 'North Ferriby', 'HU14 3BQ');

INSERT INTO supplier_contact VALUES ('CT1001', 'SP1003');
INSERT INTO supplier_contact VALUES ('CT1002', 'SP1002');
INSERT INTO supplier_contact VALUES ('CT1003', 'SP1002');
INSERT INTO supplier_contact VALUES ('CT1004', 'SP1002');
INSERT INTO supplier_contact VALUES ('CT1005', 'SP1003');
INSERT INTO supplier_contact VALUES ('CT1006', 'SP1003');
INSERT INTO supplier_contact VALUES ('CT1007', 'SP1001');
INSERT INTO supplier_contact VALUES ('CT1008', 'SP1001');
INSERT INTO supplier_contact VALUES ('CT1009', 'SP1001');
INSERT INTO supplier_contact VALUES ('CT1010', 'SP1002');

INSERT INTO stock VALUES ('ST1001', 'F1002', 'SP1003');
INSERT INTO stock VALUES ('ST1002', 'F1005', 'SP1003');
INSERT INTO stock VALUES ('ST1003', 'F1003', 'SP1001');
INSERT INTO stock VALUES ('ST1004', 'F1001', 'SP1002');
INSERT INTO stock VALUES ('ST1005', 'F1006', 'SP1001');
INSERT INTO stock VALUES ('ST1006', 'F1004', 'SP1003');
INSERT INTO stock VALUES ('ST1007', 'F1001', 'SP1002');
INSERT INTO stock VALUES ('ST1008', 'F1003', 'SP1001');

INSERT INTO rental VALUES ('R1001', 'ST1002', 'CS1004', 'SF1002', '2019-11-26', '2019-12-03');
INSERT INTO rental VALUES ('R1002', 'ST1007', 'CS1002', 'SF1003', '2019-11-28', '2019-12-04');
INSERT INTO rental VALUES ('R1003', 'ST1001', 'CS1005', 'SF1009', '2019-11-28', '2019-12-03');
INSERT INTO rental VALUES ('R1004', 'ST1005', 'CS1001', 'SF1010', '2019-11-29', '2019-12-05');
INSERT INTO rental VALUES ('R1005', 'ST1004', 'CS1008', 'SF1005', '2019-11-30', '2019-12-07');

-- Permissions
CREATE ROLE IF NOT EXISTS Manager, Assistant_Manager, Sales_Assistant;

GRANT CREATE, SELECT, UPDATE, DELETE, INSERT ON film_rental.* TO Manager;

GRANT SELECT, UPDATE, INSERT ON film_rental.category TO Assistant_Manager;
GRANT SELECT ON film_rental.contact TO Assistant_Manager;
GRANT SELECT, UPDATE, INSERT ON film_rental.customer TO Assistant_Manager;
GRANT SELECT, UPDATE, INSERT ON film_rental.film TO Assistant_Manager;
GRANT SELECT, UPDATE, INSERT ON film_rental.rating TO Assistant_Manager;
GRANT SELECT, UPDATE, INSERT ON film_rental.rental TO Assistant_Manager;
GRANT SELECT ON film_rental.staff TO Assistant_Manager;
GRANT SELECT, UPDATE, INSERT ON film_rental.stock TO Assistant_Manager;
GRANT SELECT ON film_rental.supplier TO Assistant_Manager;
GRANT SELECT ON film_rental.supplier_contact TO Assistant_Manager;

GRANT SELECT ON film_rental.category TO Sales_Assistant;
GRANT SELECT ON film_rental.contact TO Sales_Assistant;
GRANT SELECT, UPDATE, INSERT ON film_rental.customer TO Sales_Assistant;
GRANT SELECT ON film_rental.film TO Sales_Assistant;
GRANT SELECT ON film_rental.rating TO Sales_Assistant;
GRANT SELECT, UPDATE, INSERT ON film_rental.rental TO Sales_Assistant;
GRANT SELECT ON film_rental.stock TO Sales_Assistant;
GRANT SELECT ON film_rental.supplier TO Sales_Assistant;
GRANT SELECT ON film_rental.supplier_contact TO Sales_Assistant;

CREATE USER IF NOT EXISTS 'DomGepson'@'localhost' IDENTIFIED BY '1';
CREATE USER IF NOT EXISTS 'DebGoodley'@'localhost' IDENTIFIED BY '1';
CREATE USER IF NOT EXISTS 'ZacherieYates'@'localhost' IDENTIFIED BY '1';
CREATE USER IF NOT EXISTS 'HewMathelon'@'localhost' IDENTIFIED BY '1';
CREATE USER IF NOT EXISTS 'GabrieleHeatherington'@'localhost' IDENTIFIED BY '1';
CREATE USER IF NOT EXISTS 'DennaAire'@'localhost' IDENTIFIED BY '1';
CREATE USER IF NOT EXISTS 'GreggGooley'@'localhost' IDENTIFIED BY '1';
CREATE USER IF NOT EXISTS 'PaddyCollar'@'localhost' IDENTIFIED BY '1';
CREATE USER IF NOT EXISTS 'JohnSmith'@'localhost' IDENTIFIED BY '1';
CREATE USER IF NOT EXISTS 'SmithLiptrot'@'localhost' IDENTIFIED BY '1';

GRANT Sales_Assistant TO 'DomGepson'@'localhost';
GRANT Manager TO 'DebGoodley'@'localhost';
GRANT Sales_Assistant TO 'ZacherieYates'@'localhost';
GRANT Sales_Assistant TO 'HewMathelon'@'localhost';
GRANT Sales_Assistant TO 'GabrieleHeatherington'@'localhost';
GRANT Assistant_Manager TO 'DennaAire'@'localhost';
GRANT Assistant_Manager TO 'GreggGooley'@'localhost';
GRANT Sales_Assistant TO 'PaddyCollar'@'localhost';
GRANT Manager TO 'JohnSmith'@'localhost';
GRANT Assistant_Manager TO 'SmithLiptrot'@'localhost';

FLUSH PRIVILEGES;
