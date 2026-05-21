SET NAMES utf8;

SET time_zone = '+07:00';

-- Dummy data for roles table
INSERT INTO
    `roles` (
        `id`,
        `name`,
        `created_at`,
        `updated_at`
    )
VALUES (
        1,
        'Super Admin',
        NOW(),
        NOW()
    ),
    (2, 'Admin HRD', NOW(), NOW()),
    (3, 'Employee', NOW(), NOW());

-- Dummy data for menus table
INSERT INTO
    `menus` (
        `id`,
        `id_menu`,
        `name`,
        `type`,
        `status`,
        `route_name`,
        `sort`,
        `icon`
    )
VALUES (
        1,
        NULL,
        'Dashboard',
        'main',
        'active',
        'dashboard',
        1,
        'fa-home'
    ),
    (
        2,
        NULL,
        'Employees',
        'main',
        'active',
        'employees.index',
        2,
        'fa-users'
    ),
    (
        3,
        NULL,
        'HR',
        'main',
        'active',
        NULL,
        3,
        'fa-folder'
    ),
    (
        4,
        3,
        'Transport Allowances',
        'sub',
        'active',
        'transport-allowances.index',
        1,
        'fa-money-bill-wave'
    ),
    (
        5,
        3,
        'Settings',
        'sub',
        'active',
        'transport-settings.index',
        2,
        'fa-cog'
    );

-- Dummy data for employees table
INSERT INTO
    `employees` (
        `id`,
        `employee_code`,
        `full_name`,
        `email`,
        `phone`,
        `birth_place`,
        `birth_date`,
        `gender`,
        `marital_status`,
        `children_count`,
        `kecamatan`,
        `kabupaten`,
        `provinsi`,
        `address`,
        `distance_km`,
        `position`,
        `employment_status`,
        `department`,
        `join_date`,
        `is_active`
    )
VALUES (
        '0a9a6b6a-3b4b-4b2b-8b1b-0b9b8b7b6b5b',
        'EMP-001',
        'Budi Santoso',
        'budi.santoso@example.com',
        '081234567890',
        'Jakarta',
        '1990-01-01',
        'male',
        'kawin',
        2,
        'Cilandak',
        'Jakarta Selatan',
        'DKI Jakarta',
        'Jl. TB Simatupang No. 1',
        10.5,
        'manager',
        'permanent',
        'executive',
        '2015-01-15',
        1
    ),
    (
        '1b8b7b6b-4c5c-5c3c-9c2c-1c8c7c6c5c4c',
        'EMP-002',
        'Ani Yudhoyono',
        'ani.yudhoyono@example.com',
        '081234567891',
        'Bandung',
        '1992-02-02',
        'female',
        'tidak kawin',
        0,
        'Coblong',
        'Bandung',
        'Jawa Barat',
        'Jl. Dago No. 2',
        5.2,
        'staf',
        'permanent',
        'marketing',
        '2018-03-20',
        1
    ),
    (
        '2c7c6c5c-5d6d-6d4d-0d3d-2d7d6d5d4d3d',
        'EMP-003',
        'Cici Paramida',
        'cici.paramida@example.com',
        '081234567892',
        'Surabaya',
        '1995-03-03',
        'female',
        'tidak kawin',
        0,
        'Gubeng',
        'Surabaya',
        'Jawa Timur',
        'Jl. Raya Gubeng No. 3',
        15.0,
        'staf',
        'contract',
        'production',
        '2020-07-01',
        1
    ),
    (
        '3d6d5d4d-6e7e-7e5e-1e4e-3d6d5d4d3d2d',
        'EMP-004',
        'Dedi Mizwar',
        'dedi.mizwar@example.com',
        '081234567893',
        'Medan',
        '1988-04-04',
        'male',
        'kawin',
        3,
        'Medan Baru',
        'Medan',
        'Sumatera Utara',
        'Jl. Jamin Ginting No. 4',
        8.7,
        'manager',
        'permanent',
        'hrd',
        '2014-11-10',
        1
    ),
    (
        '4e5e4e3e-7f8f-8f6f-2f5f-4e5e4e3e2e1e',
        'EMP-005',
        'Eko Patrio',
        'eko.patrio@example.com',
        '081234567894',
        'Semarang',
        '2000-05-05',
        'male',
        'tidak kawin',
        0,
        'Banyumanik',
        'Semarang',
        'Jawa Tengah',
        'Jl. Setiabudi No. 5',
        2.1,
        'magang',
        'intern',
        'hrd',
        '2023-01-10',
        1
    );

-- Dummy data for users table
INSERT INTO
    `users` (
        `id`,
        `employee_id`,
        `username`,
        `email`,
        `phone`,
        `password`,
        `is_active`
    )
VALUES (
        'a1b2c3d4-e5f6-a7b8-c9d0-e1f2a3b4c5d6',
        '0a9a6b6a-3b4b-4b2b-8b1b-0b9b8b7b6b5b',
        'superadmin',
        'superadmin@example.com',
        '081111111111',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        1
    ), -- password
    (
        'b2c3d4e5-f6a7-b8c9-d0e1-f2a3b4c5d6a7',
        '3d6d5d4d-6e7e-7e5e-1e4e-3d6d5d4d3d2d',
        'adminhrd',
        'adminhrd@example.com',
        '082222222222',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        1
    ), -- password
    (
        'c3d4e5f6-a7b8-c9d0-e1f2-a3b4c5d6a7b8',
        '1b8b7b6b-4c5c-5c3c-9c2c-1c8c7c6c5c4c',
        'aniyudhoyono',
        'ani.yudhoyono@example.com',
        '081234567891',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        1
    );
-- password

-- Dummy data for accesses table
-- Super Admin (role_id 1) has full access to all menus
INSERT INTO
    `accesses` (
        `id_role`,
        `id_menu`,
        `read`,
        `view`,
        `create`,
        `update`,
        `delete`,
        `publish`
    )
VALUES (
        1,
        1,
        'allow',
        'allow',
        'allow',
        'allow',
        'allow',
        'allow'
    ),
    (
        1,
        2,
        'allow',
        'allow',
        'allow',
        'allow',
        'allow',
        'allow'
    ),
    (
        1,
        3,
        'allow',
        'allow',
        'allow',
        'allow',
        'allow',
        'allow'
    ),
    (
        1,
        4,
        'allow',
        'allow',
        'allow',
        'allow',
        'allow',
        'allow'
    ),
    (
        1,
        5,
        'allow',
        'allow',
        'allow',
        'allow',
        'allow',
        'allow'
    );

-- Admin HRD (role_id 2) has access to HR menus
INSERT INTO
    `accesses` (
        `id_role`,
        `id_menu`,
        `read`,
        `view`,
        `create`,
        `update`,
        `delete`,
        `publish`
    )
VALUES (
        2,
        1,
        'allow',
        'allow',
        'deny',
        'deny',
        'deny',
        'deny'
    ),
    (
        2,
        2,
        'allow',
        'allow',
        'allow',
        'allow',
        'deny',
        'deny'
    ),
    (
        2,
        3,
        'allow',
        'allow',
        'allow',
        'allow',
        'allow',
        'allow'
    ),
    (
        2,
        4,
        'allow',
        'allow',
        'allow',
        'allow',
        'allow',
        'allow'
    ),
    (
        2,
        5,
        'allow',
        'allow',
        'allow',
        'allow',
        'allow',
        'allow'
    );

-- Employee (role_id 3) has limited access
INSERT INTO
    `accesses` (
        `id_role`,
        `id_menu`,
        `read`,
        `view`,
        `create`,
        `update`,
        `delete`,
        `publish`
    )
VALUES (
        3,
        1,
        'allow',
        'allow',
        'deny',
        'deny',
        'deny',
        'deny'
    );

-- Dummy data for employee_educations table
INSERT INTO
    `employee_educations` (
        `id`,
        `employee_id`,
        `level`,
        `institution`,
        `major`,
        `graduation_year`
    )
VALUES (
        UUID(),
        '0a9a6b6a-3b4b-4b2b-8b1b-0b9b8b7b6b5b',
        'S1',
        'Universitas Indonesia',
        'Manajemen',
        2012
    ),
    (
        UUID(),
        '1b8b7b6b-4c5c-5c3c-9c2c-1c8c7c6c5c4c',
        'S1',
        'Institut Teknologi Bandung',
        'Desain Komunikasi Visual',
        2014
    ),
    (
        UUID(),
        '1b8b7b6b-4c5c-5c3c-9c2c-1c8c7c6c5c4c',
        'SMA',
        'SMA Negeri 3 Bandung',
        'IPA',
        2010
    );

-- Dummy data for parent_data and child_data
INSERT INTO
    `parent_data` (`id`, `employee_id`, `name`)
VALUES (
        1,
        '0a9a6b6a-3b4b-4b2b-8b1b-0b9b8b7b6b5b',
        'Ayah Budi'
    ),
    (
        2,
        '0a9a6b6a-3b4b-4b2b-8b1b-0b9b8b7b6b5b',
        'Ibu Budi'
    );

INSERT INTO
    `child_data` (`parent_id`, `name`)
VALUES (1, 'Anak Pertama Budi'),
    (1, 'Anak Kedua Budi'),
    (
        2,
        'Anak Pertama Budi dari Ibu'
    );

-- Dummy data for transport_settings table
INSERT INTO
    `transport_settings` (
        `id`,
        `base_fare`,
        `created_by`,
        `updated_by`
    )
VALUES (
        UUID(),
        50000.00,
        'a1b2c3d4-e5f6-a7b8-c9d0-e1f2a3b4c5d6',
        'a1b2c3d4-e5f6-a7b8-c9d0-e1f2a3b4c5d6'
    );

-- Dummy data for transport_allowances table
INSERT INTO
    `transport_allowances` (
        `id`,
        `employee_id`,
        `month`,
        `year`,
        `base_fare`,
        `distance_km`,
        `work_days`,
        `total_amount`,
        `created_by`,
        `updated_by`
    )
VALUES (
        UUID(),
        '0a9a6b6a-3b4b-4b2b-8b1b-0b9b8b7b6b5b',
        4,
        2024,
        50000.00,
        10.5,
        20,
        (50000.00 + (10.5 * 2000)) * 20,
        'b2c3d4e5-f6a7-b8c9-d0e1-f2a3b4c5d6a7',
        'b2c3d4e5-f6a7-b8c9-d0e1-f2a3b4c5d6a7'
    ),
    (
        UUID(),
        '1b8b7b6b-4c5c-5c3c-9c2c-1c8c7c6c5c4c',
        4,
        2024,
        50000.00,
        5.2,
        22,
        (50000.00 + (5.2 * 2000)) * 22,
        'b2c3d4e5-f6a7-b8c9-d0e1-f2a3b4c5d6a7',
        'b2c3d4e5-f6a7-b8c9-d0e1-f2a3b4c5d6a7'
    );