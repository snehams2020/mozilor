<?php
        namespace App\Http\Controllers\API;
 
        use Illuminate\Http\Request;
        use App\Http\Controllers\API\BaseController as BaseController;
        use App\Models\Product;
        use Validator;
        use App\Http\Resources\Product as ProductResource;
 
        class ProductController extends BaseController
        {
            /**
            * Display a listing of the resource.
            *
            * @return \Illuminate\Http\Response
            */
            public function index()
            {
                $products = Product::all();
 
                return $this->sendResponse(ProductResource::collection($products), 'Products retrieved successfully.');
            }
 
            public function importCSV(Request $request)
            {
                $validator = Validator::make($request->all(), [
                    'csv_file' => 'required|file|mimes:csv,txt',
                ]);
            
                if ($validator->fails()) {
                    return response()->json(['error' => $validator->errors()], 400);
                }
            
                if ($request->hasFile('csv_file')) {
                    $csvPath = $request->file('csv_file')->getRealPath();
                    $csvData = array_map('str_getcsv', file($csvPath));
                    
                    foreach ($csvData as $row) {
                        Product::create([
                            'name' => $row[0], // CSV column 1
                            'price' => $row[1], // CSV column 2
                            'SKU' => $row[2], // CSV column 3
                            'description' => $row[3], // CSV column 4
                        ]);
                    }
            
                    return response()->json(['message' => 'Products imported successfully'], 200);
                } else {
                    return response()->json(['error' => 'CSV file not found'], 404);
                }
            }
 
        }
    

