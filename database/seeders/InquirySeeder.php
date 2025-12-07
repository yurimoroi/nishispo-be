<?php

namespace Database\Seeders;

use App\Modules\Admin\Inquiry\Models\Inquiry;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InquirySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Inquiry::factory()->count(100)->create();
    }
}
