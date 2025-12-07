<?php

namespace App\Modules\Company\Controllers;

use App\Http\Controllers\Controller;

use App\Modules\Company\Repositories\CategoryRepository;
use App\Modules\Company\Services\CompanyService;

class CategoryController extends Controller
{
    public function __construct(
        protected CompanyService $companyService,
        protected CategoryRepository $categoryRepository
    ) {}

    public function index()
    {
        return $this->companyService->categoryWithArticles();
    }

    public function show(string $id) 
    {
        return $this->companyService->categoryArticles($id);
    }

    public function showByName(string $name) 
    {
        return $this->companyService->getByName($name);
    }


    public function categories()
    {
        return $this->companyService->categories();
    }
}
