<?php

namespace Database\Seeders;

use App\Enums\UserContributorStatus;
use App\Modules\User\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $developersAdminAcounts = [
            ['family_name' => 'Rose Angela', 'email' => 'rosedela@mtechlabs.dev', 'secretariat_flg' => 1, 'nickname' => "Rose", "login_id" => "mtecRose"],
            ['family_name' => 'EJ', 'email' => 'earlsimo@mtechlabs.dev', 'secretariat_flg' => 1, 'nickname' => "EJ", "login_id" => "mtechEJ"],
            ['family_name' => 'Michael', 'email' => 'michtoge@mtechlabs.dev', 'secretariat_flg' => 1, 'nickname' => "Michael", "login_id" => "mtechMichael"],
            ['family_name' => 'Joseph', 'email' => 'joseengl@mtechlabs.dev', 'secretariat_flg' => 1, 'nickname' => "Joseph", "login_id" => "mtechJoseph"],
            ['family_name' => 'Benz', 'email' => 'benzsuma@mtechlabs.dev', 'secretariat_flg' => 1, 'nickname' => "Benz", "login_id" => "mtechBenz"],
            ['family_name' => 'Yui', 'email' => 'yuichiro@mtechlabs.dev', 'secretariat_flg' => 1, 'nickname' => "Yui", "login_id" => "mtechYui"],
            ['family_name' => 'Seima', 'email' => 'seimangorira@gmail.com', 'secretariat_flg' => 1, 'nickname' => "Seima", "login_id" => "mtechSeima"],
        ];

        $developersContributorAcounts = [
            ['family_name' => 'Rose Angela', 'email' => 'rosedela+1@mtechlabs.dev', 'secretariat_flg' => 0, 'nickname' => "Rose", "login_id" => "mtecRoseContributor", "contributor_status" => UserContributorStatus::approved()->value, "contributor_name" => "Mtech Contributory"],
            ['family_name' => 'EJ', 'email' => 'earlsimo+1@mtechlabs.dev', 'secretariat_flg' => 0, 'nickname' => "EJ", "login_id" => "mtechEJContributor", "contributor_status" => UserContributorStatus::approved()->value, "contributor_name" => "Mtech Contributory"],
            ['family_name' => 'Michael', 'email' => 'michtoge+1@mtechlabs.dev', 'secretariat_flg' => 0, 'nickname' => "Michael", "login_id" => "mtechMichaelContributor", "contributor_status" => UserContributorStatus::approved()->value, "contributor_name" => "Mtech Contributory"],
            ['family_name' => 'Joseph', 'email' => 'joseengl+1@mtechlabs.dev', 'secretariat_flg' => 0, 'nickname' => "Joseph", "login_id" => "mtechJosephContributor", "contributor_status" => UserContributorStatus::approved()->value, "contributor_name" => "Mtech Contributory"],
            ['family_name' => 'Benz', 'email' => 'benzsuma+1@mtechlabs.dev', 'secretariat_flg' => 0, 'nickname' => "Benz", "login_id" => "mtechBenzContributor", "contributor_status" => UserContributorStatus::approved()->value, "contributor_name" => "Mtech Contributory"],
            ['family_name' => 'Yui', 'email' => 'yuichiro+1@mtechlabs.dev', 'secretariat_flg' => 0, 'nickname' => "Yui", "login_id" => "mtechYuiContributor", "contributor_status" => UserContributorStatus::approved()->value, "contributor_name" => "Mtech Contributory"],
            ['family_name' => 'Seima', 'email' => 'seimangorira+1@gmail.com', 'secretariat_flg' => 0, 'nickname' => "Seima", "login_id" => "mtechSeimaContributor", "contributor_status" => UserContributorStatus::approved()->value, "contributor_name" => "Mtech Contributory"],
        ];

        array_map(fn($admins) => User::factory()->create($admins), $developersAdminAcounts);

        array_map(fn($contributors) => User::factory()->create($contributors), $developersContributorAcounts);

        User::factory()->count(30)->create();
    }
}
