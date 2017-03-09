<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Mail;
use App\Mail\contacts;
use App\wishList;

class HomeController extends Controller {
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    //  public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct() {
        return view('front.home');
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view('front.home');
    }

    public function shop(Request $request) {
        if ($request->ajax() && isset($request->start)) {
            $start = $request->start; // min price value
            $end = $request->end; // max price value

            $Products = DB::table('products')
                    ->where('pro_price', '>=', $start)
                    ->where('pro_price', '<=', $end)
                    ->orderby('pro_price', 'ASC')
                    ->paginate(6);

             response()->json($Products); //return to ajax
            return view('front.products', compact('Products'));
        } 
        else if(isset($request->brand)){
             
           $brand = $request->brand; //brand

            $Products = DB::table('products')
                    ->whereIN('cat_id', explode( ',', $brand )) 
                    ->paginate(6);
            
           // dd($Products);
         
             response()->json($Products); //return to ajax
            return view('front.products', compact('Products'));

            
        }
        else {

            $Products = DB::table('products')->paginate(6); // now we are fetching all products
            return view('front.shop', compact('Products'));
        }
    }

    public function proCats(Request $request) {
        $catName = $request->name;
        $Products = DB::table('pro_cat')->leftJoin('products', 'pro_cat.id', '=', 'products.cat_id')->where('pro_cat.name', '=', $catName)->paginate(12);

        return view('front.shop', compact('Products'));
    }

    Public function product_details($id) {

        $Products = DB::table('products')->where('id', $id)->get();

        return view('front.product_details', compact('Products'));
    }

    public function contact() {
        return view('front.contact');
    }

    public function search(Request $request) {
        $search = $request->search_data;
        if ($search == '') {
            return view('front.home');
        } else {
            $Products = DB::table('products')->where('pro_name', 'like', '%' . $search . '%')->paginate(12);
            return view('front.shop', ['msg' => 'Results: ' . $search], compact('Products'));
        }
    }

    public function sendmail() {
        $mail = 'hardeepphp@yahoo.com';
        Mail::to($mail)->send(new contacts);
        dd('mail sent');
    }

    public function wishList(Request $request) {


        $wishList = new wishList;
        $wishList->user_id = Auth::user()->id;
        $wishList->pro_id = $request->pro_id;


        $wishList->save();

        $Products = DB::table('products')->where('id', $request->pro_id)->get();
        //$Products = DB::table('wishlist')->leftJoin('products', 'wishlist.pro_id', '=', 'products.ic')->get();

        return view('front.product_details', compact('Products'));
    }

    public function View_wishList() {

        $Products = DB::table('wishlist')->leftJoin('products', 'wishlist.pro_id', '=', 'products.id')->get();
        return view('front.wishList', compact('Products'));
    }

    public function removeWishList($id) {

        DB::table('wishlist')->where('pro_id', '=', $id)->delete();

        return back()->with('msg', 'Item Removed from Wishlist');
    }

}
