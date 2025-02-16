<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Helpers\Encrypt;

class Customer extends Model
{
    use SoftDeletes;

    protected $table = "customers";
    protected $primarykey = "id";

    protected $guarded = [
        'id'
    ];

    public static function getList($filters)
    {
        try {
            $query = self::orderBy('name', 'asc');

            if ($filters['gender']) {
                $query->where('gender', $filters['gender']);
            }
            if ($filters['customerCountry']) {
                $query->where('country', $filters['customerCountry']);
            }

            return $query;

        } catch (\Exception $e) {
            Log::error('Failed to get data: ' . $e->getMessage());    
            return false;
        }
    }

    public static function addCustomer(array $params = [])
    {
        try {
            return DB::transaction(function () use ($params) {
                $data = array(
                    'name' => $params['name'],
                    'email' => $params['email'],
                    'phone' => $params['phone'],
                    'birth_place' => $params['pob'],
                    'birth_date' => date('Y-m-d', strtotime($params['dob'])),
                    'country' => $params['country'],
                    'gender' => $params['gender'],
                );
                $customer = self::create($data);

                if (!$customer) {
                    abort(400, 'Failed to add new Customer');
                }
            
                return $customer;
            });
        } catch (\Exception $e) {
            Log::error('Failed to add new Customer: ' . $e->getMessage());    
            return false;
        }
    }

    public static function updateCustomer($params)
    {
        try {
            $encrypt = new Encrypt;
            $id = $encrypt->encrypt_decrypt($params['customerId'], 'decrypt'); // Decrypt ID

            $customer = self::find($params['customerId']);

            if (!$customer) {
                return [
                    'success' => false,
                    'message' => 'Customer not found',
                    'status' => 400,
                ];
            }

            $customer->update([
                'name' => $params['name'],
                'email' => $params['email'],
                'phone' => $params['phone'],
                'birth_place' => $params['pob'],
                'birth_date' => date('Y-m-d', strtotime($params['dob'])),
                'country' => $params['country'],
                'gender' => $params['gender'],
            ]);

            return [
                'success' => true,
                'message' => 'Customer successfully updated',
                'data' => $customer,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to update Customer: ' . $e->getMessage(),
                'status' => 500,
            ];
        }
    }

    public static function deleteCustomer($idCustomer)
    {
        try {
            $customer = self::find($idCustomer);

            if (!$customer) {
                return [
                    'success' => false,
                    'message' => 'Customer not found',
                    'status' => 404,
                ];
            }

            $customer->delete();

            return [
                'success' => true,
                'message' => 'Customer successfully deleted',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to delete Customer: ' . $e->getMessage(),
                'status' => 500,
            ];
        }
    }
}
