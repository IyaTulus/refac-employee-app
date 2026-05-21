SET NAMES utf8;

SET time_zone = '+00:00';

SET foreign_key_checks = 0;

CREATE TABLE IF NOT EXISTS `activities` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `title` varchar(255) NOT NULL,
    `content` mediumtext DEFAULT NULL,
    `ua` varchar(256) DEFAULT NULL,
    `ip` varchar(64) DEFAULT NULL,
    `url` text DEFAULT NULL,
    `browser` varchar(64) DEFAULT NULL,
    `platform` varchar(64) DEFAULT NULL,
    `negara` varchar(64) DEFAULT NULL,
    `provinsi` varchar(64) DEFAULT NULL,
    `kabupaten` varchar(64) DEFAULT NULL,
    `kecamatan` varchar(64) DEFAULT NULL,
    `kelurahan` varchar(64) DEFAULT NULL,
    `latitude` double unsigned DEFAULT NULL,
    `longitude` double unsigned DEFAULT NULL,
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    `created_by` int(11) DEFAULT NULL,
    `updated_by` int(11) DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_520_nopad_ci;

CREATE TABLE IF NOT EXISTS `file` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `name` varchar(256) NOT NULL,
    `mime` varchar(256) NOT NULL,
    `size` varchar(32) NOT NULL,
    `path` text NOT NULL,
    `parent_id` bigint(20) NOT NULL,
    `parent_table` varchar(256) NOT NULL,
    `parent_field` varchar(256) NOT NULL,
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    `created_by` int(11) DEFAULT NULL,
    `updated_by` int(11) DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `created_by` (`created_by`),
    KEY `updated_by` (`updated_by`),
    KEY `index` (
        `parent_id`,
        `parent_table`,
        `parent_field`
    ) USING BTREE,
    CONSTRAINT `file_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `file_ibfk_2` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_520_nopad_ci;

CREATE TABLE IF NOT EXISTS `jobs` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `queue` varchar(255) NOT NULL,
    `payload` longtext NOT NULL,
    `attempts` tinyint(3) unsigned NOT NULL,
    `reserved_at` int(10) unsigned DEFAULT NULL,
    `available_at` int(10) unsigned NOT NULL,
    `created_at` int(10) unsigned NOT NULL,
    PRIMARY KEY (`id`),
    KEY `jobs_queue_index` (`queue`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `job_batches` (
    `id` varchar(255) NOT NULL,
    `name` varchar(255) NOT NULL,
    `total_jobs` int(11) NOT NULL,
    `pending_jobs` int(11) NOT NULL,
    `failed_jobs` int(11) NOT NULL,
    `failed_job_ids` longtext NOT NULL,
    `options` mediumtext DEFAULT NULL,
    `cancelled_at` int(11) DEFAULT NULL,
    `created_at` int(11) NOT NULL,
    `finished_at` int(11) DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `migrations` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `migration` varchar(255) NOT NULL,
    `batch` int(11) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
    `email` varchar(255) NOT NULL,
    `token` varchar(255) NOT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`email`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `roles` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(128) NOT NULL,
    `created_at` datetime DEFAULT current_timestamp(),
    `updated_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_520_nopad_ci;

CREATE TABLE IF NOT EXISTS `accesses` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `id_role` int(11) NOT NULL,
    `id_menu` int(11) NOT NULL,
    `read` varchar(16) DEFAULT NULL,
    `view` varchar(16) DEFAULT NULL,
    `create` varchar(16) DEFAULT NULL,
    `update` varchar(16) DEFAULT NULL,
    `delete` varchar(16) DEFAULT NULL,
    `publish` varchar(16) DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `unik` (`id_role`, `id_menu`),
    KEY `web_level_admin_menus_ibfk_1` (`id_menu`),
    CONSTRAINT `id_menu_fk` FOREIGN KEY (`id_menu`) REFERENCES `menus` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `id_role_fk` FOREIGN KEY (`id_role`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_520_nopad_ci;

CREATE TABLE IF NOT EXISTS `menus` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `id_menu` int(11) DEFAULT NULL,
    `name` varchar(128) NOT NULL,
    `type` varchar(16) NOT NULL,
    `status` varchar(16) NOT NULL,
    `route_name` varchar(256) DEFAULT NULL,
    `route_params` varchar(256) DEFAULT NULL,
    `href` varchar(256) DEFAULT NULL,
    `sort` tinyint(4) DEFAULT NULL,
    `icon` varchar(128) DEFAULT NULL,
    `target` varchar(8) DEFAULT NULL,
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `id_menu` (`id_menu`) USING BTREE,
    KEY `indek` (`id_menu`, `type`, `status`) USING BTREE,
    CONSTRAINT `menus_id_menu_fk` FOREIGN KEY (`id_menu`) REFERENCES `menus` (`id`) ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_520_nopad_ci ROW_FORMAT = COMPACT;

CREATE TABLE IF NOT EXISTS `sessions` (
    `id` varchar(255) NOT NULL,
    `user_id` bigint(20) unsigned DEFAULT NULL,
    `ip_address` varchar(45) DEFAULT NULL,
    `user_agent` text DEFAULT NULL,
    `payload` longtext NOT NULL,
    `last_activity` int(11) NOT NULL,
    PRIMARY KEY (`id`),
    KEY `sessions_user_id_index` (`user_id`),
    KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE employees (
    id CHAR(36) PRIMARY KEY,
    photo VARCHAR(255) NULL,
    employee_code VARCHAR(255) UNIQUE NOT NULL,
    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    phone VARCHAR(255) UNIQUE NOT NULL,
    birth_place VARCHAR(255) NOT NULL,
    birth_date DATE NOT NULL,
    gender ENUM('male', 'female') NOT NULL,
    marital_status ENUM('kawin', 'tidak kawin') NOT NULL,
    children_count INT DEFAULT 0 NOT NULL,
    kecamatan VARCHAR(255) NOT NULL,
    kabupaten VARCHAR(255) NOT NULL,
    provinsi VARCHAR(255) NOT NULL,
    address TEXT NOT NULL,
    distance_km DECIMAL(8, 2) DEFAULT 0 NOT NULL,
    position ENUM('manager', 'staf', 'magang') NOT NULL,
    employment_status ENUM(
        'contract',
        'permanent',
        'intern'
    ) NOT NULL,
    department ENUM(
        'marketing',
        'hrd',
        'production',
        'executive',
        'commissioner'
    ) NOT NULL,
    join_date DATE NOT NULL,
    resign_date DATE NULL,
    is_active BOOLEAN DEFAULT TRUE NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

CREATE INDEX employees_employee_code_index ON employees (employee_code);

CREATE INDEX employees_full_name_index ON employees (full_name);

CREATE INDEX employees_gender_index ON employees (gender);

CREATE INDEX employees_marital_status_index ON employees (marital_status);

CREATE INDEX employees_position_index ON employees (position);

CREATE INDEX employees_employment_status_index ON employees (employment_status);

CREATE INDEX employees_department_index ON employees (department);

CREATE INDEX employees_join_date_index ON employees (join_date);

CREATE INDEX employees_is_active_index ON employees (is_active);

CREATE TABLE users (
    id CHAR(36) PRIMARY KEY,
    employee_id CHAR(36) NULL UNIQUE,
    username VARCHAR(255) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    phone VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    is_active BOOLEAN DEFAULT 0 NOT NULL,
    remember_token VARCHAR(100) NULL,
    last_login_at TIMESTAMP NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    CONSTRAINT users_employee_id_foreign FOREIGN KEY (employee_id) REFERENCES employees (id) ON DELETE SET NULL
);

CREATE TABLE employee_educations (
    id CHAR(36) PRIMARY KEY,
    employee_id CHAR(36) NOT NULL,
    level VARCHAR(255) NOT NULL,
    institution VARCHAR(255) NOT NULL,
    major VARCHAR(255) NULL,
    graduation_year YEAR NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    CONSTRAINT employee_educations_employee_id_foreign FOREIGN KEY (employee_id) REFERENCES employees (id) ON DELETE CASCADE
);

CREATE TABLE parent_data (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    employee_id CHAR(36) NOT NULL,
    name VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    CONSTRAINT parent_data_employee_id_foreign FOREIGN KEY (employee_id) REFERENCES employees (id) ON DELETE CASCADE
);

CREATE TABLE child_data (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    parent_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    CONSTRAINT child_data_parent_id_foreign FOREIGN KEY (parent_id) REFERENCES parent_data (id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE transport_settings (
    id CHAR(36) PRIMARY KEY,
    base_fare DECIMAL(15, 2) DEFAULT 0 NOT NULL,
    created_by CHAR(36) NULL,
    updated_by CHAR(36) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    CONSTRAINT transport_settings_created_by_foreign FOREIGN KEY (created_by) REFERENCES users (id) ON DELETE SET NULL,
    CONSTRAINT transport_settings_updated_by_foreign FOREIGN KEY (updated_by) REFERENCES users (id) ON DELETE SET NULL
);

CREATE TABLE transport_allowances (
    id CHAR(36) PRIMARY KEY,
    employee_id CHAR(36) NOT NULL,
    month TINYINT NOT NULL,
    year INT NOT NULL,
    base_fare DECIMAL(15, 2) NOT NULL,
    distance_km DECIMAL(8, 2) NOT NULL,
    work_days INT NOT NULL,
    total_amount DECIMAL(15, 2) NOT NULL,
    notes TEXT NULL,
    created_by CHAR(36) NULL,
    updated_by CHAR(36) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    UNIQUE (employee_id, month, year),
    CONSTRAINT transport_allowances_employee_id_foreign FOREIGN KEY (employee_id) REFERENCES employees (id) ON DELETE CASCADE,
    CONSTRAINT transport_allowances_created_by_foreign FOREIGN KEY (created_by) REFERENCES users (id) ON DELETE SET NULL,
    CONSTRAINT transport_allowances_updated_by_foreign FOREIGN KEY (updated_by) REFERENCES users (id) ON DELETE SET NULL
);