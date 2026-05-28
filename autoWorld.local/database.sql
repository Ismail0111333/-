-- =============================================
-- database.sql
-- База данных AutoWorld
-- Лабораторная работа №4
-- =============================================

CREATE DATABASE IF NOT EXISTS autoworld_db
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_general_ci;

USE autoworld_db;

DROP TABLE IF EXISTS test_drive_requests;
DROP TABLE IF EXISTS cars;

CREATE TABLE cars (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    subtitle VARCHAR(255) DEFAULT NULL,
    description TEXT,
    price INT NOT NULL,
    image VARCHAR(255) DEFAULT NULL,
    specs JSON DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE test_drive_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    car_model VARCHAR(100) DEFAULT NULL,
    date DATE NOT NULL,
    time TIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO cars (name, subtitle, description, price, image, specs) VALUES
('Tesla Model S', 'Электрический седан бизнес-класса', 'Tesla Model S — один из самых узнаваемых электромобилей в мире.', 8999000, 'img/tesla_model_s.jpg', '["Запас хода: до 600 км", "Разгон 0-100 км/ч: 2.1 сек", "Мощность: 1020 л.с.", "Привод: полный"]'),
('BMW i4', 'Электрический седан с динамикой спорткара', 'BMW i4 — это воплощение динамики и эффективности.', 5499000, 'img/bmw_i4.jpg', '["Запас хода: до 590 км", "Разгон 0-100 км/ч: 3.9 сек", "Мощность: 544 л.с.", "Привод: полный"]'),
('Audi e-tron', 'Премиальный электрический внедорожник', 'Audi e-tron — это комфорт, качество и передовые технологии.', 6999000, 'img/audi_etron.jpg', '["Запас хода: до 436 км", "Разгон 0-100 км/ч: 5.7 сек", "Мощность: 408 л.с.", "Привод: полный"]'),
('Porsche Taycan', 'Спортивный электромобиль от Porsche', 'Porsche Taycan доказывает, что электромобили могут быть спорткарами.', 10999000, 'img/porsche_taycan.jpg', '["Запас хода: до 504 км", "Разгон 0-100 км/ч: 2.8 сек", "Мощность: 761 л.с.", "Привод: полный"]'),
('Ford Mustang Mach-E', 'Электрический кроссовер', 'Ford Mustang Mach-E — сочетание легендарного имени и современных технологий.', 4999000, 'img/ford_mustang_mach_e.jpg', '["Запас хода: до 483 км", "Разгон 0-100 км/ч: 3.5 сек", "Мощность: 480 л.с.", "Привод: полный"]'),
('Hyundai Ioniq 6', 'Футуристичный электромобиль', 'Hyundai Ioniq 6 — аэродинамичный седан с инновационным дизайном.', 3999000, 'img/hyundai_ioniq_6.jpg', '["Запас хода: до 614 км", "Разгон 0-100 км/ч: 5.1 сек", "Мощность: 325 л.с.", "Привод: полный"]');