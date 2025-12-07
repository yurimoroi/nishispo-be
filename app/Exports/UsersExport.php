<?php

namespace App\Exports;

use App\Enums\UserContributorStatus;
use App\Filters\UserContributorFilter;
use App\Filters\UserRoleFilter;
use App\Filters\UserSearchFilter;
use App\Repositories\UserRepository;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Http\JsonResponse;
use Maatwebsite\Excel\Concerns\WithMapping;
use Spatie\QueryBuilder\AllowedFilter;

class UsersExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(protected UserRepository $userRepository) {}
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $data = $this->userRepository->all(
            with: ['organizations'],
            paginate: false,
            allowedFilters: [
                AllowedFilter::custom('role', new UserRoleFilter()),
                AllowedFilter::custom('status', new UserContributorFilter),
                AllowedFilter::exact('organization', 'organization.id'),
                AllowedFilter::custom('search',  new UserSearchFilter)
            ]
        );

        return $data; // Return the mapped data as a collection
    }

    public function map($users): array
    {
        $orgs = [];
        foreach ($users->organizations as $org) {
            $orgs[] = $org->name;
        }

        return [
            $users->family_name . " " . $users->given_name,
            $users->nickname,
            $users->login_id,
            $users->email,
            "N/A",
            implode(",", $orgs),
            UserContributorStatus::from($users->contributor_status)->label,
            "N/A"
        ];
    }
    /**
     * Define column headings for the exported Excel file.
     *
     * @return array
     */
    public function headings(): array
    {
        // Return the list of column headings that will be used in the Excel export
        return [
            'Account Name   ', // Account name in Japanese
            'Nickname',    // Nickname in Japanese
            'Login ID',      // Login ID in Japanese
            'Email',  // Email in Japanese
            'Permissions',            // Permissions in Japanese
            'Organizations',        // Organization in Japanese
            'Contributor Status', // Contributor status in Japanese
            'Club Feature', // Club feature status in Japanese
        ];
    }
}
