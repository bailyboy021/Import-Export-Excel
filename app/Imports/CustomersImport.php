<?php

namespace App\Imports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class CustomersImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $existingCustomer = Customer::withTrashed()
            ->where('email', $row['email'])
            ->orWhere('phone', $row['phone'])
            ->first();

        if ($existingCustomer) {
            if ($existingCustomer->trashed()) {
                $existingCustomer->restore(); // Restore jika soft deleted
            }

            // Update data customer yang sudah ada
            $existingCustomer->update([
                'name'        => $row['name'],
                'birth_place' => $row['birth_place'],
                'birth_date'  => date('Y-m-d', strtotime($row['birth_date'])),
                'gender'      => $row['gender'],
                'country'     => $row['country'],
            ]);

            return $existingCustomer;
        }

        // Jika tidak ada, buat customer baru
        return new Customer([
            'name'        => $row['name'],
            'email'       => $row['email'],
            'phone'       => $row['phone'],
            'birth_place' => $row['birth_place'],
            'birth_date'  => date('Y-m-d', strtotime($row['birth_date'])),
            'gender'      => $row['gender'],
            'country'     => $row['country'],
        ]);
    }

    public function rules(): array
    {
        return [
            "name"          => "required|string",
            "email"         => "required|email|unique:customers,deleted_at,NULL",
            "phone"         => "required|string|unique:customers,deleted_at,NULL",
            "birth_place"   => "required|string",
            "birth_date"    => "required|date",
            "gender"        => "required|string|in:Male,Female",
            "country"       => "required|string|in:Indonesia,Palestine,Turkey,Italy,Japan",
        ];
    }
}
