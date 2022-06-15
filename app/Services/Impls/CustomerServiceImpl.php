<?php

namespace App\Services\Impls;

use Exception;
use App\Models\Customer;

use App\Actions\RandomGenerator;
use App\Services\CustomerService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

class CustomerServiceImpl implements CustomerService
{
    public function __construct()
    {
        
    }
    
    public function create(
        int $company_id,
        int $customer_group_id,
        string $code,
        int $is_member,
        string $name,
        ?string $zone = null,
        int $max_open_invoice,
        int $max_outstanding_invoice,
        int $max_invoice_age,
        int $payment_term,
        int $tax_id,
        ?string $remarks = null,
        int $status,
    ): ?Customer
    {
        DB::beginTransaction();
        $timer_start = microtime(true);

        try {
            if ($code == Config::get('const.DEFAULT.KEYWORDS.AUTO')) {
                $code = $this->generateUniqueCode($company_id);
            }

            $customer = new Customer();
            $customer->company_id = $company_id;
            $customer->customer_group_id = $customer_group_id;
            $customer->code = $code;
            $customer->is_member = $is_member;
            $customer->name = $name;
            $customer->zone = $zone;
            $customer->max_open_invoice = $max_open_invoice;
            $customer->max_outstanding_invoice = $max_outstanding_invoice;
            $customer->max_invoice_age = $max_invoice_age;
            $customer->payment_term = $payment_term;
            $customer->tax_id = $tax_id;
            $customer->remarks = $remarks;
            $customer->status = $status;

            $customer->save();

            DB::commit();

            $this->flushCache();

            return $customer;
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

            $customer = Customer::with('company')
                        ->whereCompanyId($companyId);
    
            if (empty($search)) {
                $customer = $customer->latest();
            } else {
                $customer = $customer->where('name', 'like', '%'.$search.'%')->latest();
            }
    
            if ($paginate) {
                $perPage = is_numeric($perPage) ? $perPage : Config::get('const.DEFAULT.PAGINATION_LIMIT');
                $result = $customer->paginate($perPage);
            } else {
                $result = $customer->get();
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
        int $customer_group_id,
        string $code,
        int $is_member,
        string $name,
        ?string $zone = null,
        int $max_open_invoice,
        int $max_outstanding_invoice,
        int $max_invoice_age,
        int $payment_term,
        int $tax_id,
        ?string $remarks = null,
        int $status,
    ): ?Customer
    {
        DB::beginTransaction();
        $timer_start = microtime(true);

        try {
            $customer = Customer::find($id);

            if ($code == Config::get('const.DEFAULT.KEYWORDS.AUTO')) {
                $code = $this->generateUniqueCode($company_id);
            }
    
            $customer->update([
                'company_id' => $company_id,
                'customer_group_id' => $customer_group_id,
                'code' => $code,
                'is_member' => $is_member,
                'name' => $name,
                'zone' => $zone,
                'max_open_invoice' => $max_open_invoice,
                'max_outstanding_invoice' => $max_outstanding_invoice,
                'max_invoice_age' => $max_invoice_age,
                'payment_term' => $payment_term,
                'tax_id' => $tax_id,
                'remarks' => $remarks,
                'status' => $status,
            ]);

            DB::commit();

            $this->flushCache();

            return $customer->refresh();
        } catch (Exception $e) {
            DB::rollBack();
            Log::debug('['.session()->getId().'-'.(is_null(auth()->user()) ? '':auth()->id()).'] '.__METHOD__.$e);
            return Config::get('const.ERROR_RETURN_VALUE');
        } finally {
            $execution_time = microtime(true) - $timer_start;
            Log::channel('perfs')->info('['.session()->getId().'-'.' '.'] '.__METHOD__.' ('.number_format($execution_time, 1).'s)');
        }
    }

    public function delete(int $id): bool
    {
        DB::beginTransaction();
        $timer_start = microtime(true);

        $retval = false;
        try {
            $customer = Customer::find($id);

            if ($customer) {
                $retval = $customer->delete();
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
            Log::channel('perfs')->info('['.session()->getId().'-'.' '.'] '.__METHOD__.' ('.number_format($execution_time, 1).'s)');
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
        $result = Customer::whereCompanyId($companyId)->where('code', '=' , $code);

        if($exceptId)
            $result = $result->where('id', '<>', $exceptId);

        return $result->count() == 0 ? true:false;
    }
}