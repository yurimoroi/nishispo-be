<?php

namespace Database\Seeders;

use App\Modules\Admin\Inquiry\Models\InquiryType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InquiryTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    const inquiryTypes = [
        "about_article_content",
        "regarding_recruitment",
        "about_advertising",
        "about_sponsorship_support",
        "about_services_functions",
        "about_supporters",
        "applications_groupware",
        "inquiries_operating_company",
        "other_inquiries"
    ];

    public function run(): void
    {
        array_map(fn($inquiryType) => InquiryType::create(["name" => __($inquiryType)]), self::inquiryTypes);
    }
}
