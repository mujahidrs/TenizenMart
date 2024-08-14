@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @if(Auth::user()->roles[0]->name == 'kantin')
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-header">{{ __('Table to Action') }}</div>
        
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Customer Name</th>
                                        <th>Invoice Number</th>
                                        <th>Action</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($invoices as $key => $invoice)
                                        <tr data-key="{{ $key }}">
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $invoice->user->name }}</td>
                                            <td>{{ $invoice->invoice_number }}</td>
                                            <td>
                                                <button onclick="showDetail(event)" class="btn btn-info">Detail</button>
                                                <a onclick="return confirm('Apakah Anda yakin ingin menyelesaikan pesanan ini?')" href="{{ route('completeTransaction', $invoice->invoice_number) }}" class="btn btn-success">Complete</a>
                                            </td>
                                            <td>{{ $invoice->status }}</td>
                                        </tr>
                                        <tr data-key="{{ $invoice->invoice_number }}" style="display: none">
                                            <td colspan="4">
                                                @foreach ($invoice->details as $detail)
                                                    <div class="row">
                                                        <div class="col">
                                                            {{ $detail->product->name }}
                                                        </div>
                                                        <div class="col">
                                                            {{ $detail->quantity }}
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="card">
                        <div class="card-header">{{ __('Table to Information') }}</div>
        
                        <div class="card-body">
                        </div>
                    </div>
                </div>
            </div>
            @endif
            @if(Auth::user()->roles[0]->name == 'bank')
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-header">
                            Table to Action
                        </div>
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Name</th>
                                        <th>Request</th>
                                        <th>Nominal</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($wallet_requests as $key => $request)
                                        <tr>
                                            <td>{{ $key+1 }}</td>
                                            <td>{{ $request->user->name }}</td>
                                            <td>{{ $request->description }}</td>
                                            <td>{{ $request->income - $request->outcome }}</td>
                                            <td>
                                                <a href="{{ route('acceptWalletRequest', $request->id) }}" class="btn btn-success">Accept</a>
                                                <a href="{{ route('rejectWalletRequest', $request->id) }}" class="btn btn-danger">Reject</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

<script>
    function showDetail(event){
        // console.log(event.target.parentNode.parentNode.nextElementSibling);
        let nextElementSibling = event.target.parentNode.parentNode.nextElementSibling;
        if(nextElementSibling.style.display == 'none'){
            nextElementSibling.style.display = 'table-row';
        }
        else {
            nextElementSibling.style.display = 'none';
        }
    }
</script>