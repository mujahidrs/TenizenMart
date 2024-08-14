<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LandingPageController extends Controller
{
    public function index()
    {
        $categories = Category::with('products')->get();
        $carts = Transaction::where('status', 'on cart')->get();
        $saldo = 0;

        //Cek jika sudah login
        if(Auth::check()){
            $saldo = $this->checkSaldo();
        }

        return view('welcome', compact('categories', 'carts', 'saldo'));
    }

    public function search(Request $request)
    {
        $keyword = $request->keyword;

        $categories = Category::with('products')->get();

        $categories = Category::with(['products' => function($query) use ($keyword) {
            $query->where('name', 'like', "%$keyword%")->orWhere('description', 'like', "%$keyword%");
        }])->get();

        $carts = Transaction::where('status', 'on cart')->get();

        $saldo = 0;

        //Cek jika sudah login
        if(Auth::check()){
            $saldo = $this->checkSaldo();
        }

        return view('welcome', compact('categories', 'carts', 'saldo'));
    }

    public function store($id)
    {
        $product = Product::find($id);

        if($product->stock >= 1){
            $addToCart = Transaction::create([
                'product_id' => $product->id,
                'user_id' => Auth::user()->id,
            ]);
    
            $product->update([
                'stock' => $product->stock - 1
            ]);
            
            if($addToCart){
                return redirect()->back()->with('status', 'Product added to cart successfully.');
            }
        }
        else {
            return redirect()->back()->with('status', 'Stock habis')->with('color', 'danger');
        }

    }

    public function update(Request $request, $id){
        $id = $request->id;
        $recent_quantity = Transaction::find($id)->quantity;
        $new_quantity = $request->quantity;

        //Pengembalian stock
        $product = Product::find(Transaction::find($id)->product_id);
        $product->update([
            'stock' => $product->stock + $recent_quantity
        ]);

        //Update quantity di transaksi
        Transaction::find($id)->update([
            'quantity' => $new_quantity
        ]);

        //Update stock di product
        $product->update([
            'stock' => $product->stock - $new_quantity
        ]);

        return redirect()->back()->with('status', 'Quantity updated successfully.');
    }

    public function checkSaldo(){
        $saldo = 0;
        $wallets = Wallet::where('user_id', Auth::user()->id)
                        ->where('status', 'success')
                        ->get();
        foreach($wallets as $wallet) {
            $income = $wallet->income;
            $outcome = $wallet->outcome;
            $recent = $income - $outcome;
            $saldo += $recent;
        }

        return $saldo;
    }

    public function totalCart(){
        $total_belanja = 0;
        $carts = Transaction::where('user_id', Auth::user()->id)->where('status', 'on cart')->get();
        foreach($carts as $cart) {
            $harga_total = $cart->quantity * $cart->product->price;
            $total_belanja += $harga_total;
        }

        return $total_belanja;
    }

    public function checkout()
    {
        $saldo = $this->checkSaldo();

        $total_belanja = $this->totalCart();

        // $carts = Transaction::where('user_id', Auth::user()->id)->where('status', 'on cart')->get();

        // foreach($carts as $cart) {
        //     if($cart->quantity > $cart->product->stock){
        //         return redirect()->back()->with('status', 'Permintaan Anda melebihi stok produk, silahkan edit pesanan')->with('color', 'danger');
        //     }
        // }

        // dd($total_belanja, $saldo);

        if($total_belanja > $saldo){
            return redirect()->back()->with('status', 'Saldo Anda tidak mencukupi');
        }
        else{
            $invoice_number = "INV_" . date('YmdHis') . Auth::user()->id;
    
            Transaction::where('user_id', Auth::user()->id)->where('status', 'on cart')->update([
                'invoice_number' => $invoice_number,
                'status' => 'paid'
            ]);

            //Pengurangan saldo
            Wallet::create([
                'user_id' => Auth::user()->id,
                'outcome' => $total_belanja,
                'description' => 'Pembayaran Transaksi'
            ]);

            // //Pengurangan stock
            // foreach($carts as $cart) {
            //     $recent_stock = Product::where('id', $cart->product_id)->first()->stock;
            //     $new_stock = $recent_stock - $cart->quantity;
            //     Product::where('id', $cart->product_id)->update([
            //         'stock' => $new_stock
            //     ]);
            // }

            return redirect()->back()->with('status', 'Berhasil melakukan checkout');
        }

    }

    public function destroy($cart_id){
        $cart = Transaction::find($cart_id);
        $quantity = $cart->quantity;
        $product_id = $cart->product_id;
        $cart->delete();

        //Kembalikan stock
        $product = Product::find($product_id);
        $product->update([
            'stock' => $product->stock + $quantity
        ]);
    
        return redirect()->back()->with('status', 'Berhasil menghapus produk dari keranjang');
    }

    public function topUpSaldo(Request $request)
    {
        Wallet::create([
            'user_id' => Auth::user()->id,
            'income' => $request->nominal,
            'description' => 'Top-up Saldo',
            'status' => 'pending'
        ]);

        return redirect()->back()->with('status', 'Berhasil melakukan permintaan top-up saldo');
    }

    public function tarikTunai(Request $request)
    {
        Wallet::create([
            'user_id' => Auth::user()->id,
            'outcome' => $request->nominal,
            'description' => 'Tarik Tunai',
            'status' => 'pending'
        ]);

        return redirect()->back()->with('status', 'Berhasil melakukan permintaan tarik tunai');
    }
}