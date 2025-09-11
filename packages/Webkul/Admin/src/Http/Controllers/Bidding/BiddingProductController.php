<?php

namespace Webkul\Admin\Http\Controllers\Bidding;

use Webkul\Admin\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BiddingProductController extends Controller
{

    public function index()
    {
        $products = DB::table('bidding_products')
            ->select('bidding_products.id', 'bidding_products.name as product', 'category_translations.name as category', 'bidding_products.price')
            ->leftJoin('category_translations', 'bidding_products.category', '=', 'category_translations.category_id')
            ->paginate(10);

        return view('admin::bidding.products.index', compact('products'));
        /*if (request()->ajax()) {

            $products = DB::table('bidding_products')
                ->select('bidding_products.id', 'bidding_products.name as product', 'category_translations.name as category', 'bidding_products.price')
                ->leftJoin('category_translations', 'bidding_products.category', '=', 'category_translations.category_id')
                ->paginate(10);

            print_r($products);
    
            return $products->toJson();
        }

        return view('admin::bidding.products.index');*/
    }

    public function create()
    {

        return view('admin::bidding.products.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'category' => 'required|string',
            'status' => 'required|string',
            'price' => 'required|numeric',
            'starting_bid' => 'required|numeric',
            'images.*' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
        ]);

        // Handle images upload
        $imageFilenames = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('bidding-products', 'public'); // stores in storage/app/public/bidding-products
                $imageFilenames[] = $path;
            }
        }

        $data['images'] = $imageFilenames;

        // Save data to database
        BiddingProduct::create($data);

        // Redirect with success message
        return redirect()->route('admin.bidding.products.index')->with('success', 'Bidding product created successfully.');
    }

}

