CREATE DATABASE IF NOT EXISTS wshop
CHARACTER SET utf8mb4
COLLATE utf8mb4_general_ci;

USE wshop;

SET NAMES 'utf8mb4';

CREATE TABLE IF NOT EXISTS type (
    id INT AUTO_INCREMENT PRIMARY KEY,
    label VARCHAR(100) NOT NULL
);

INSERT INTO type (label) VALUES
('Sportwear'), ('Clothing'), ('Bookstore'), ('DIY store'), ('Grocery store')
ON DUPLICATE KEY UPDATE id=id;

CREATE TABLE IF NOT EXISTS shop (
    id CHAR(36) PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slogan VARCHAR(255) DEFAULT NULL,
    type_id INT,
    address VARCHAR(255) NOT NULL,
    opening_hour TIME NOT NULL,
    closing_hour TIME NOT NULL,
    FOREIGN KEY (type_id) REFERENCES type(id)
);

INSERT INTO shop (id, name, slogan, type_id, address, opening_hour, closing_hour) VALUES
(UUID(), 'Decathlon', 'À fond la forme !', 1, '3 Rue des Peupliers', '09:00:00', '17:30:00'),
(UUID(), 'Zara', 'Love your curves', 2, '10 Impasse du Lys', '09:30:00', '17:30:00'),
(UUID(), 'Go Sport', 'La marque qui vit le sport et le fait vivre à ses clients', 1, '54 Rue des Peupliers', '10:00:00', '18:00:00'),
(UUID(), 'Fnac', 'Libérons la culture', 3, '31 Allée de la Toison d''Or', '09:30:00', '17:30:00'),
(UUID(), 'Leroy Merlin', 'On peut tout construire ensemble, même l''avenir', 4, '2 Allée de la Toison d''Or', '10:00:00', '17:30:00'),
(UUID(), 'Balenciaga', 'Soyez Balenciaga, pas les tendances', 2, '108 Impasse du Lys', '09:00:00', '17:30:00'),
(UUID(), 'Gilbert Joseph', 'La culture c''est l''échange', 3, '14 Rue des Peupliers', '09:00:00', '18:00:00'),
(UUID(), 'Carrefour', 'On a tous droit au meilleur', 5, '104 Boulevard des Myosotis', '10:00:00', '18:00:00'),
(UUID(), 'Intermarché', 'Tous unis contre la vie chère', 5, '107 Boulevard des Myosotis', '08:30:00', '16:30:00'),
(UUID(), 'The Kooples', 'Un vestiaire pour deux', 2, '1bis Allée du Chêne', '08:00:00', '16:30:00')
ON DUPLICATE KEY UPDATE id=id;