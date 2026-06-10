<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Room;
use App\Models\Bhp;
use App\Models\Asset;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // 1. Create Default Users
        $users = [
            [
                'name' => 'System Administrator',
                'email' => 'admin@capstone.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ],
            [
                'name' => 'Dr. Ahmad (Kepala Lab)',
                'email' => 'kalab@capstone.com',
                'password' => Hash::make('password'),
                'role' => 'kalab',
            ],
            [
                'name' => 'Prof. Budi (Kaprodi)',
                'email' => 'kaprodi@capstone.com',
                'password' => Hash::make('password'),
                'role' => 'kaprodi',
            ],
            [
                'name' => 'Siti (Staf Administrasi)',
                'email' => 'staf_admin@capstone.com',
                'password' => Hash::make('password'),
                'role' => 'staf_admin',
            ],
            [
                'name' => 'Deni (Staf Laboratorium)',
                'email' => 'staf_lab@capstone.com',
                'password' => Hash::make('password'),
                'role' => 'staf_lab',
            ]
        ];

        foreach ($users as $u) {
            User::updateOrCreate(['email' => $u['email']], $u);
        }

        // 2. Create Default Rooms
        $room1 = Room::updateOrCreate(
            ['code' => 'LAB-KOM-1'],
            ['name' => 'Lab Komputer 1', 'description' => 'Laboratorium Komputer Jaringan dan Pemrograman']
        );

        $room2 = Room::updateOrCreate(
            ['code' => 'LAB-BIO'],
            ['name' => 'Lab Biologi', 'description' => 'Laboratorium Mikrobiologi dan Genetika']
        );

        $room3 = Room::updateOrCreate(
            ['code' => 'LAB-FIS'],
            ['name' => 'Lab Fisika', 'description' => 'Laboratorium Fisika Dasar']
        );

        // 3. Create Default BHP Items
        $bhps = [
            ['name' => 'Kabel UTP Cat6', 'stock' => 5, 'unit' => 'roll'],
            ['name' => 'Alcohol Swab', 'stock' => 100, 'unit' => 'box'],
            ['name' => 'Masker Medis', 'stock' => 15, 'unit' => 'box'],
            ['name' => 'Hand Sanitizer 500ml', 'stock' => 20, 'unit' => 'botol'],
        ];

        foreach ($bhps as $b) {
            Bhp::updateOrCreate(['name' => $b['name']], $b);
        }

        // 4. Create Default Assets
        $assets = [
            [
                'room_id' => $room1->id,
                'name' => 'PC Lenovo ThinkCentre',
                'code' => 'KOM-PC-001',
                'status' => 'baik',
                'price' => 12500000.00,
                'purchase_date' => '2025-01-10',
            ],
            [
                'room_id' => $room1->id,
                'name' => 'PC Lenovo ThinkCentre',
                'code' => 'KOM-PC-002',
                'status' => 'rusak',
                'price' => 12500000.00,
                'purchase_date' => '2025-01-10',
            ],
            [
                'room_id' => $room2->id,
                'name' => 'Mikroskop Binokuler',
                'code' => 'BIO-MIK-001',
                'status' => 'baik',
                'price' => 8500000.00,
                'purchase_date' => '2024-06-15',
            ],
            [
                'room_id' => $room3->id,
                'name' => 'Neraca Ohaus',
                'code' => 'FIS-NER-001',
                'status' => 'maintenance',
                'price' => 4500000.00,
                'purchase_date' => '2023-09-20',
            ]
        ];

        foreach ($assets as $a) {
            Asset::updateOrCreate(['code' => $a['code']], $a);
        }
    }
}
