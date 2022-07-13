<?php

namespace Tests\Feature\API;

use Exception;
use App\Models\Role;
use App\Models\Unit;
use App\Models\User;
use Tests\APITestCase;
use App\Models\Company;
use App\Enums\UserRoles;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Foundation\Testing\WithFaker;

class UnitAPITest extends APITestCase
{
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
    }

    #region store

    public function test_unit_api_call_store_expect_successful()
    {
        /** @var \Illuminate\Contracts\Auth\Authenticatable */
        $user = User::factory()
                    ->hasAttached(Role::where('name', '=', UserRoles::DEVELOPER->value)->first())
                    ->has(Company::factory()->setIsDefault(), 'companies')
                    ->create();

        $companyId = $user->companies->first()->id;

        $this->actingAs($user);

        $unitArr = array_merge([
            'company_id' => Hashids::encode($companyId),
        ], Unit::factory()->make()->toArray());

        $api = $this->json('POST', route('api.post.db.product.unit.save'), $unitArr);

        $api->assertSuccessful();
        $this->assertDatabaseHas('units', [
            'company_id' => Hashids::decode($unitArr['company_id'])[0],
            'code' => $unitArr['code'],
            'name' => $unitArr['name'],
            'description' => $unitArr['description'],
            'category' => $unitArr['category'],
        ]);
    }
}
