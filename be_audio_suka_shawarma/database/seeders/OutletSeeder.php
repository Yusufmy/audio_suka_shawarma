<?php

namespace Database\Seeders;

use App\Models\Outlet;
use Illuminate\Database\Seeder;

class OutletSeeder extends Seeder
{
    public function run(): void
    {
        $outlets = [
            [
                'code' => 'OTL-001',
                'name' => 'Outlet Empang',
                'is_busy' => false,
            ],
            [
                'code' => 'OTL-002',
                'name' => 'Outlet BCC',
                'is_busy' => false,
            ],
            [
                'code' => 'OTL-003',
                'name' => 'Outlet Paledang',
                'is_busy' => false,
            ],
            [
                'code' => 'OTL-004',
                'name' => 'Outlet Dramaga',
                'is_busy' => false,
            ],
            [
                'code' => 'OTL-005',
                'name' => 'Outlet Cicurug',
                'is_busy' => false,
            ],
            [
                'code' => 'OTL-006',
                'name' => 'Outlet Cibinong',
                'is_busy' => false,
            ],
            [
                'code' => 'OTL-007',
                'name' => 'Outlet Ciseeng',
                'is_busy' => false,
            ],
            [
                'code' => 'OTL-008',
                'name' => 'Outlet Sentul',
                'is_busy' => false,
            ],
            [
                'code' => 'OTL-009',
                'name' => 'Outlet Pajajaran',
                'is_busy' => false,
            ],
            [
                'code' => 'OTL-010',
                'name' => 'Outlet Pekayon',
                'is_busy' => false,
            ],
            [
                'code' => 'OTL-011',
                'name' => 'Outlet Jatiasih',
                'is_busy' => false,
            ],
            [
                'code' => 'OTL-012',
                'name' => 'Outlet Jatiwaringin',
                'is_busy' => false,
            ],
            [
                'code' => 'OTL-013',
                'name' => 'Outlet Kali Sari',
                'is_busy' => false,
            ],
            [
                'code' => 'OTL-014',
                'name' => 'Outlet Cibubur',
                'is_busy' => false,
            ],
            [
                'code' => 'OTL-015',
                'name' => 'Outlet Cileungsi',
                'is_busy' => false,
            ],
            [
                'code' => 'OTL-016',
                'name' => 'Outlet Sukamajaya',
                'is_busy' => false,
            ],
            [
                'code' => 'OTL-017',
                'name' => 'Outlet Beji',
                'is_busy' => false,
            ],
            [
                'code' => 'OTL-018',
                'name' => 'Outlet Sawangan',
                'is_busy' => false,
            ],
            [
                'code' => 'OTL-019',
                'name' => 'Outlet Cirendeu',
                'is_busy' => false,
            ],
            [
                'code' => 'OTL-020',
                'name' => 'Outlet Jagaraksa',
                'is_busy' => false,
            ],
        ];

        foreach ($outlets as $outlet) {
            Outlet::updateOrCreate(
                [
                    'code' => $outlet['code'],
                ],
                [
                    'name' => $outlet['name'],
                    'status' => 'offline',
                    'is_busy' => $outlet['is_busy'],
                    'last_seen_at' => null,
                    'paired_at' => null,
                    'device_info' => null,
                ]
            );
        }
    }
}
