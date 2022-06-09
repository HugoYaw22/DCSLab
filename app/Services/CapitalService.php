<?php

namespace App\Services;

use App\Models\Capital;

interface CapitalService
{
    public function create(
        int $company_id,
        int $investor_id,
        int $group_id,
        ?int $cash_id = null,
        ?int $ref_number = null,
        string $date,
        int $capial_status,
        int $amount,
        ?string $remarks = null,
    ): ?Capital;

    public function read(
        int $companyId,
        string $search = '',
        bool $paginate = true,
        int $page,
        int $perPage = 10,
        bool $useCache = true
    );

    public function update(
        int $id,
        int $company_id,
        int $investor_id,
        int $group_id,
        ?int $cash_id = null,
        ?int $ref_number = null,
        string $date,
        int $capial_status,
        int $amount,
        ?string $remarks = null,
    ): ?Capital;

    public function delete(int $id): bool;

    public function generateUniqueCode(int $companyId): string;

    public function isUniqueCode(string $code, int $companyId, ?int $exceptId = null): bool;
}