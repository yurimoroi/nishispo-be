<?php

namespace App\Exports;

use App\Modules\User\Models\User;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UsersDistributionPoint implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $userRepo = new UserRepository(new User());

        $startDate = Carbon::parse(request('start_date', now()))->startOfDay();
        $endDate = Carbon::parse(request('end_date', now()))->endOfDay();
        
        $users = User::withPublishedArticlesInRange($startDate, $endDate)->get();

        $users = $userRepo->distributionPointBodyCharactersCount($users);

        return $users;
    }

    public function map($users): array
    {
        return [
            $users->id,
            $users->phonetic_family_name,
            $users->phonetic_given_name,
            '--',
            $users->articles_count,
            $users->total_body_characters

        ];
    }

    public function headings(): array
    {
       
        return [
            'No.',
            '姓',   
            '名',     
            '楽天ID',  
            '公開記事数',           
            '総文字数',       
        ];
    }
}
