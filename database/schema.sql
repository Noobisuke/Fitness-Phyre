-- =====================================================================
-- Fitness Phyre - database schema (MySQL 5.7+ / MariaDB 10.3+)
-- Import this once in phpMyAdmin (Import tab) after creating an empty
-- database. It is used from Phase 5 onwards.
-- No sample data and no default admin password are included on purpose:
-- the admin account is created with a setup script in Phase 6.
-- =====================================================================

SET NAMES utf8mb4;

-- People who can log in to the admin panel
CREATE TABLE IF NOT EXISTS admins (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username      VARCHAR(50)  NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,              -- from password_hash(), never plain text
    created_at    TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    last_login_at DATETIME     NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Failed admin logins, used to slow down password guessing
CREATE TABLE IF NOT EXISTS login_attempts (
    id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ip_address   VARCHAR(45) NOT NULL,
    attempted_at DATETIME    NOT NULL,
    INDEX idx_ip_time (ip_address, attempted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Messages from the Contact page
CREATE TABLE IF NOT EXISTS enquiries (
    id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name           VARCHAR(100) NOT NULL,
    phone          VARCHAR(20)  NOT NULL,
    email          VARCHAR(150) NULL,
    interested_in  VARCHAR(50)  NOT NULL,
    preferred_time VARCHAR(50)  NULL,
    message        TEXT         NULL,
    is_contacted   TINYINT(1)   NOT NULL DEFAULT 0,   -- 0 = new, 1 = contacted
    ip_address     VARCHAR(45)  NULL,                 -- used for rate limiting
    created_at     TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_contacted (is_contacted),
    INDEX idx_created (created_at),
    INDEX idx_ip_created (ip_address, created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Requests from the Book a Trial page (a request, NOT a confirmed booking)
CREATE TABLE IF NOT EXISTS trial_requests (
    id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name           VARCHAR(100) NOT NULL,
    phone          VARCHAR(20)  NOT NULL,
    email          VARCHAR(150) NULL,
    preferred_date DATE         NULL,
    preferred_time VARCHAR(50)  NULL,
    fitness_goal   VARCHAR(100) NULL,
    message        TEXT         NULL,
    is_contacted   TINYINT(1)   NOT NULL DEFAULT 0,
    ip_address     VARCHAR(45)  NULL,
    created_at     TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_contacted (is_contacted),
    INDEX idx_created (created_at),
    INDEX idx_ip_created (ip_address, created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Optional (Phase 7): gallery images managed from the admin panel.
CREATE TABLE IF NOT EXISTS gallery (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    filename    VARCHAR(150) NOT NULL,
    caption     VARCHAR(150) NULL,
    category    VARCHAR(30)  NOT NULL DEFAULT 'gym',  -- gym, equipment, training, classes, community
    sort_order  INT          NOT NULL DEFAULT 0,
    created_at  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- NOTE: there is deliberately NO reviews table. Reviews stay on Google;
-- the site only links to them, so nothing can be fabricated.
