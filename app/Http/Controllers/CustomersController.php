<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Customer;
use App\Helpers\Encrypt;
use App\Helpers\Mimetype;
use DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CustomersExport;
use App\Imports\CustomersImport;

class CustomersController extends Controller
{
    public function getCustomers(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "gender" => "nullable|string|in:Male,Female",
            "customerCountry" => "nullable|string|in:Indonesia,Palestine,Turkey,Italy,Japan",
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 400);
        }

        $query = Customer::getList($validator->validated());

        $encrypt = new Encrypt();

        return DataTables::of($query)
        ->editColumn('id', function ($data) use ($encrypt){
            return $encrypt->encrypt_decrypt($data->id, 'encrypt');
        })
        ->editColumn('phone', function ($data) {
            return '+'.$data->phone;     
        })
        ->editColumn('birth_date', function ($data) {
            return date('d M Y', strtotime($data->birth_date));     
        })
        ->make(true);
    }

    public function export(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            "gender" => "nullable|string|in:Male,Female",
            "customerCountry" => "nullable|string|in:Indonesia,Palestine,Turkey,Italy,Japan",
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 400);
        }

        $validatedData = $validator->validated();
        return Excel::download(new CustomersExport($validatedData), 'customer.xlsx');
    }

    public function importCustomers()
    {
        return view('importCustomers');
    }

    public function import(Request $request) 
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx|max:2048',
        ]);

        $file = $request->file('file');
        $content = file_get_contents($file->getRealPath());
        $filename = $file->getClientOriginalName();

        if (!MimeType::whiteList($filename) || !MimeType::whiteListBytes($content, $filename)) {
            abort(415, 'File tidak valid atau tidak diizinkan.');
        }
  
        Excel::import(new CustomersImport, $request->file('file'));
                 
        return back()->with('success', 'Users imported successfully.');
    }

    public function addCustomers()
    {
        $model = new Customer();

        return view('addCustomers', compact('model'));
    }

    public function storeCustomers(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required|string",
            "email" => "required|email|unique:customers",
            "phone" => "required|string|unique:customers",
            "pob" => "required|string",
            "dob" => "required|date",
            "gender" => "required|string|in:Male,Female",
            "country" => "required|string|in:Indonesia,Palestine,Turkey,Italy,Japan",
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 400);
        }

        $data = Customer::addCustomer($validator->validated());
        return response()->json($data, 201);
    }

    public function viewCustomers(Request $request)
    {
		$encrypt = new Encrypt;
		$idCustomer = $encrypt->encrypt_decrypt($request->idCustomer, 'decrypt');
		
        $model = Customer::where('id', $idCustomer)->first();         

        $data['model'] = $model;
        $data['idCustomer'] = $request->idCustomer;
        $result = array(
            'body' =>  view('addCustomers', $data)->render()
            ,'title' => "Edit Customer - ".$model->name
        );

        echo json_encode($result);
    }

    public function updateCustomers(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "customerId" => "required|string",
            "name" => "required|string",
            "email" => "required|email",
            "phone" => "required|string",
            "pob" => "required|string",
            "dob" => "required|date",
            "gender" => "required|string|in:Male,Female",
            "country" => "required|string|in:Indonesia,Palestine,Turkey,Italy,Japan",
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 400);
        }

        $result = Customer::updateCustomer($validator->validated());

        if (!$result['success']) {
            return response()->json([
                'error' => $result['message'],
            ], $result['status']);
        }

        return response()->json([
            'message' => $result['message'],
            'data' => $result['data'],
        ], 200);
    }

    public function deleteCustomers(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:customers,id,deleted_at,NULL',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 400);
        }

        $result = Customer::deleteCustomer($request->id);

        return response()->json([
            'message' => $result['message'],
        ], 200);
    }
}
