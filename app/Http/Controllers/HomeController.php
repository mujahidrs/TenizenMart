<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if(Auth::user()->roles[0]->name == 'kantin'){
            $invoices = Transaction::where('status', 'paid')
                    ->groupBy('invoice_number')
                    ->get();

            foreach($invoices as $invoice){
                $details = Transaction::where('invoice_number', $invoice->invoice_number)->get();
                $invoice->details = $details;
            }

            return view('home', compact('invoices'));
        }
        if(Auth::user()->roles[0]->name == 'bank'){
            $wallet_requests = Wallet::where('status', 'pending')->get();

            return view('home', compact('wallet_requests'));
        }
    }

    //Fungsi untuk menampilkan mutasi wallet
    public function walletHistory()
    {
        $wallet_history = Wallet::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();

        return view('walletHistory', compact('wallet_history'));
    }

    public function acceptWalletRequest($id)
    {
        Wallet::find($id)->update([
            'status' => 'success'
        ]);

        return redirect()->back()->with('status', 'Berhasil menerima permintaan');
    }

    public function rejectWalletRequest($id)
    {
        Wallet::find($id)->delete();

        return redirect()->back()->with('status', 'Berhasil menolak permintaan');
    }

    public function completeTransaction($invoice_number)
    {
        Transaction::where('invoice_number', $invoice_number)->update([
            'status' => 'received'
        ]);

        return redirect()->back()->with('status', 'Berhasil memberikan pesanan');
    }
}
