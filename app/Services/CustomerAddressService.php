<?php

namespace App\Services;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Collection;

interface CustomerAddressService
{
    public function create(
    ): mixed;

    public function read(
    ): Paginator|Collection|null;
    
    public function readBy(string $key, string $value);

    public function update(
    ): mixed;

    public function delete(int $id): bool;
}