<?php

namespace App\Services\Impls;

use Exception;
use App\Models\CustomerGroup;

use App\Actions\RandomGenerator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Services\CustomerGroupService;
use Illuminate\Support\Facades\Config;

class CustomerGroupServiceImpl implements CustomerGroupService
{
    public function __construct()
    {
        
    }
    
    public function create(
        int $company_id,
        int $cash_id,
        string $code,
        string $name,
        int $max_open_invoice = null,
        int $max_outstanding_invoice = null,
        int $max_invoice_age = null,
        int $payment_term = null,
        int $selling_point,
        string $selling_point_multiple = null,
        ?int $sell_at_cost = null,
        int $price_markup_percent = null,
        int $price_markup_nominal = null,
        int $price_markdown_nominal,
        int $round_on = null,
        ?int $round_digit = null,
        ?int $remarks = null,
    ): ?CustomerGroup
    {
        DB::beginTransaction();
        $timer_start = microtime(true);

        try {
            if ($code == Config::get('const.DEFAULT.KEYWORDS.AUTO')) {
                $code = $this->generateUniqueCode($company_id);
            }

            $customerGroup = new CustomerGroup();
            $customerGroup->company_id = $company_id;
            $customerGroup->cash_id = $cash_id;
            $customerGroup->code = $code;
            $customerGroup->name = $name;
            $customerGroup->max_open_invoice = $max_open_invoice;
            $customerGroup->max_outstanding_invoice = $max_outstanding_invoice;
            $customerGroup->max_invoice_age = $max_invoice_age;
            $customerGroup->selling_point = $selling_point;
            $customerGroup->selling_point_multiple = $selling_point_multiple;
            $customerGroup->sell_at_cost = $sell_at_cost;
            $customerGroup->price_markup_percent = $price_markup_percent;
            $customerGroup->price_markup_nominal = $price_markup_nominal;
            $customerGroup->price_markdown_nominal = $price_markdown_nominal;
            $customerGroup->round_on = $round_on;
            $customerGroup->round_digit = $round_digit;
            $customerGroup->remarks = $remarks;

            $customerGroup->save();

            DB::commit();

            $this->flushCache();

            return $customerGroup;
        } catch (Exception $e) {
            DB::rollBack();
            Log::debug('['.session()->getId().'-'.(is_null(auth()->user()) ? '':auth()->id()).'] '.__METHOD__.$e);
            return Config::get('const.ERROR_RETURN_VALUE');
        } finally {
            $execution_time = microtime(true) - $timer_start;
            Log::channel('perfs')->info('['.session()->getId().'-'.' '.'] '.__METHOD__.' ('.number_format($execution_time, 1).'s)');
        }
    }

    public function read(
        int $companyId,
        string $search = '',
        bool $paginate = true,
        int $page,
        int $perPage = 10,
        bool $useCache = true
    )
    {
        $timer_start = microtime(true);

        try {
            $cacheKey = '';
            if ($useCache) {
                $cacheKey = 'read_'.(empty($search) ? '[empty]':$search).'-'.$paginate.'-'.$page.'-'.$perPage;
                $cacheResult = $this->readFromCache($cacheKey);

                if (!is_null($cacheResult)) return $cacheResult;
            }

            $result = null;

            if (!$companyId) return null;

            $customergroup = CustomerGroup::with('company')
                        ->whereCompanyId($companyId);
    
            if (empty($search)) {
                $customergroup = $customergroup->latest();
            } else {
                $customergroup = $customergroup->where('name', 'like', '%'.$search.'%')->latest();
            }
    
            if ($paginate) {
                $perPage = is_numeric($perPage) ? $perPage : Config::get('const.DEFAULT.PAGINATION_LIMIT');
                $result = $customergroup->paginate($perPage);
            } else {
                $result = $customergroup->get();
            }

            if ($useCache) $this->saveToCache($cacheKey, $result);
            
            return $result;
        } catch (Exception $e) {
            Log::debug('['.session()->getId().'-'.(is_null(auth()->user()) ? '':auth()->id()).'] '.__METHOD__.$e);
            return Config::get('const.DEFAULT.ERROR_RETURN_VALUE');
        } finally {
            $execution_time = microtime(true) - $timer_start;
            Log::channel('perfs')->info('['.session()->getId().'-'.(is_null(auth()->user()) ? '':auth()->id()).'] '.__METHOD__.' ('.number_format($execution_time, 1).'s)');
        }
    }

    private function readFromCache($key)
    {
        try {
            if (!Config::get('const.DEFAULT.DATA_CACHE.ENABLED')) return Config::get('const.DEFAULT.ERROR_RETURN_VALUE');

            if (!Cache::tags([auth()->user()->id, class_basename(__CLASS__)])->has($key)) return Config::get('const.DEFAULT.ERROR_RETURN_VALUE');

            return Cache::tags([auth()->user()->id, class_basename(__CLASS__)])->get($key);
        } catch (Exception $e) {
            Log::debug('['.session()->getId().'-'.(is_null(auth()->user()) ? '':auth()->id()).'] '.__METHOD__.$e);
            return Config::get('const.DEFAULT.ERROR_RETURN_VALUE');
        } finally {
            Log::channel('cachehits')->info('['.session()->getId().'-'.(is_null(auth()->user()) ? '':auth()->id()).'] '.__METHOD__.' Key: '.$key. ', Tags: ['.auth()->user()->id.', '.class_basename(__CLASS__).']');
        }
    }

    private function saveToCache($key, $val)
    {
        try {
            if (empty($key)) return;

            Cache::tags([auth()->user()->id, class_basename(__CLASS__)])->add($key, $val, Config::get('const.DEFAULT.DATA_CACHE.CACHE_TIME.ENV'));
        } catch (Exception $e) {
            Log::debug('['.session()->getId().'-'.(is_null(auth()->user()) ? '':auth()->id()).'] '.__METHOD__.$e);
        } finally {
            Log::channel('cachehits')->info('['.session()->getId().'-'.(is_null(auth()->user()) ? '':auth()->id()).'] '.__METHOD__.' Key: '.$key. ', Tags: ['.auth()->user()->id.', '.class_basename(__CLASS__).']');
        }
    }

    private function flushCache()
    {
        try {
            Cache::tags([auth()->user()->id, class_basename(__CLASS__)])->flush();
        } catch (Exception $e) {
            Log::debug('['.session()->getId().'-'.(is_null(auth()->user()) ? '':auth()->id()).'] '.__METHOD__.$e);
        } finally {
            Log::channel('cachehits')->info('['.session()->getId().'-'.(is_null(auth()->user()) ? '':auth()->id()).'] '.__METHOD__.' Tags: ['.(is_null(auth()->user()) ? '':auth()->id()).', '.class_basename(__CLASS__).']');
        }
    }

    public function update(
        int $id,
        int $company_id,
        int $cash_id,
        string $code,
        string $name,
        int $max_open_invoice = null,
        int $max_outstanding_invoice = null,
        int $max_invoice_age = null,
        int $payment_term = null,
        int $selling_point,
        string $selling_point_multiple = null,
        ?int $sell_at_cost = null,
        int $price_markup_percent = null,
        int $price_markup_nominal = null,
        int $price_markdown_nominal,
        int $round_on = null,
        ?int $round_digit = null,
        ?int $remarks = null,
    ): ?CustomerGroup
    {
        DB::beginTransaction();
        $timer_start = microtime(true);

        try {
            $customerGroup = CustomerGroup::find($id);

            if ($code == Config::get('const.DEFAULT.KEYWORDS.AUTO')) {
                $code = $this->generateUniqueCode($company_id);
            }
    
            $customerGroup->update([
                'company_id' => $company_id,
                'cash_id' => $cash_id,
                'code' => $code,
                'name' => $name,
                'max_open_invoice' => $max_open_invoice,
                'max_outstanding_invoice' => $max_outstanding_invoice,
                'max_invoice_age' => $max_invoice_age,
                'payment_term' => $payment_term,
                'selling_point' => $selling_point,
                'selling_point_multiple' => $selling_point_multiple,
                'sell_at_cost' => $sell_at_cost,
                'price_markup_percent' => $price_markup_percent,
                'price_markup_nominal' => $price_markup_nominal,
                'price_markdown_nominal' => $price_markdown_nominal,
                'round_on' => $round_on,
                'round_digit' => $round_digit,
                'remarks' => $remarks,
            ]);

            DB::commit();

            $this->flushCache();

            return $customerGroup->refresh();
        } catch (Exception $e) {
            DB::rollBack();
            Log::debug('['.session()->getId().'-'.(is_null(auth()->user()) ? '':auth()->id()).'] '.__METHOD__.$e);
            return Config::get('const.ERROR_RETURN_VALUE');
        } finally {
            $execution_time = microtime(true) - $timer_start;
            Log::channel('perfs')->info('['.session()->getId().'-'.(is_null(auth()->user()) ? '':auth()->id()).'] '.__METHOD__.' ('.number_format($execution_time, 1).'s)');
        }
    }

    public function delete(int $id): bool
    {
        DB::beginTransaction();
        $timer_start = microtime(true);

        $retval = false;
        try {
            $customerGroup = CustomerGroup::find($id);

            if ($customerGroup) {
                $retval = $customerGroup->delete();
            }

            DB::commit();

            $this->flushCache();

            return $retval;
        } catch (Exception $e) {
            DB::rollBack();
            Log::debug('['.session()->getId().'-'.(is_null(auth()->user()) ? '':auth()->id()).'] '.__METHOD__.$e);
            return Config::get('const.ERROR_RETURN_VALUE');
        } finally {
            $execution_time = microtime(true) - $timer_start;
            Log::channel('perfs')->info('['.session()->getId().'-'.(is_null(auth()->user()) ? '':auth()->id()).'] '.__METHOD__.' ('.number_format($execution_time, 1).'s)');
        }
    }

    public function generateUniqueCode(int $companyId): string
    {
        $rand = new RandomGenerator();
        $code = '';
        
        do {
            $code = $rand->generateAlphaNumeric(3).$rand->generateFixedLengthNumber(3);
        } while (!$this->isUniqueCode($code, $companyId));

        return $code;
    }

    public function isUniqueCode(string $code, int $companyId, ?int $exceptId = null): bool
    {
        $result = CustomerGroup::whereCompanyId($companyId)->where('code', '=' , $code);

        if($exceptId)
            $result = $result->where('id', '<>', $exceptId);

        return $result->count() == 0 ? true:false;
    }
}