<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Products;
use DB;

class ProductsController extends Controller
{
    //
    public function getProducts(Request $request) {

        $a = Products::paginate(10);
        
        return $a;
    }

    public function addProduct(Request $request) {

        $r = $request->all();

        try {

            DB::beginTransaction();

            if ($request->hasFile('photo')) {

                $file = $request->file('photo');
    
                $filename = time() . '_' . $file->getClientOriginalName();
    
                $file->storeAs('uploads', $filename, 'public');
                
            }
            DB::commit();
            Products::create([
                'product_name' => $r['name'],
                'product_photo' => $filename,
            ]);

            return 'Created';
        } catch (Exception $e) {
            return $e->getMessage();
        }


    }

    public function updateProduct(Request $request) {

        $r = $request->all();

        $to_update = [
            'product_name' => $r['name']
        ];

        try {

            DB::beginTransaction();

            if ($request->hasFile('photo')) {

                $file = $request->file('photo');
    
                $filename = time() . '_' . $file->getClientOriginalName();
    
                $file->storeAs('uploads', $filename, 'public');       
                
                $to_update['product_photo'] = $filename;
            }
            DB::commit();
            Products::where('id', $r['id'])->update($to_update);

            return 'Updated';

        } catch (Exception $e) {
            DB::rollBack();

            return $e->getMessage();
        }

    }

    public function deleteProduct($id) {

        try {
            DB::beginTransaction();

            DB::commit();
            Products::where('id', $id)->delete();

            return 'Deleted';
        } catch (Exception $e) {
            return $e->getMessage();
        }

    }

}
