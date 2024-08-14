<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  </head>
  <body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
          <a class="navbar-brand" href="#">TenizenMart</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  Category
                </a>
                <ul class="dropdown-menu">
                  @foreach ($categories as $category)
                    <li><a class="dropdown-item" href="#">{{ $category->name }}</a></li>
                    @endforeach
                </ul>
              </li>
            </ul>
            <form class="d-flex me-auto" role="search" method="GET" action="/">
              {{-- @csrf --}}
              <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="keyword" value="{{ isset($_GET['keyword']) ? $_GET['keyword'] : '' }}">
              <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
            <ul class="navbar-nav mb-2 mb-lg-0">
                <li class="nav-item">
                    <a href="" class="nav-link">
                      <i class="bi bi-cart"></i>
                    </a>
                </li>
                @guest
                    <li class="nav-item">
                        <a href="{{ route('login') }}" class="btn btn-primary mx-1">Login</a>
                    </li>
                @endguest
                @auth
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                          {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu">
                        @if (Auth::user()->roles[0]->name != 'customer')
                            <li class="nav-item">
                                <a href="{{ route('home') }}" class="nav-link">Dashboard</a>
                            </li>
                        @endif
                          <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">Logout</button>
                            </form>
                        </li>

                        </ul>
                      </li>
                @endauth
            </ul>
            
          </div>
        </div>
      </nav>

      @if (session('status'))
          <div class="alert alert-{{ session('color') ? session('color') : 'success' }}">
              {{ session('status') }}
          </div>
      @endif
      <div class="row">
        <div class="col">
          @foreach ($categories as $key => $category)
            @if(count($category->products) != 0)
            <div class="card m-3 me-0" data-key="{{ $key }}">
              <div class="card-header">
                {{ $category->name }}
              </div>
              <div class="card-body">
                <div class="row">
                  @foreach ($category->products as $index => $product)
                    <div class="col-3" data-index="{{ $index }}">
                      <div class="card mb-3">
                        <div class="card-header">
                          {{ $product->name }}
                        </div>
                        <div class="card-body">
                          <img src="{{ asset('storage/'. $product->image) }}" class="card-img-top" alt="...">
                          <div class="card-body">
                            <h5 class="card-title">Rp{{ $product->price }}</h5>
                            <p class="card-text">
                              {{ $product->description }}
                              Stock: {{ $product->stock }}
                            </p>
                            <form method="POST" action="{{ route('addToCart', $product->id) }}">
                              @csrf
                              <button type="submit" href="" class="btn btn-primary" {{ $product->stock < 1 ? 'disabled' : '' }}>Add to Cart</button>
                            </form>
                          </div>
          
                        </div>
                      </div>
                    </div>
                @endforeach
                </div>
              </div>
            </div>
            @endif
          @endforeach
        </div>
        <div class="col-4">
          @auth
          @if(Auth::user()->roles[0]->name == 'customer')
          <div class="card m-3 ms-0">
            <div class="card-header">
              Wallet
            </div>
            <div class="card-body">
              Saldo: {{ $saldo }}
            </div>
            <div class="card-footer d-grid gap-2">
              <div class="btn-group">
                <!-- Button trigger modal -->
              <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#topUpSaldo">
                Top Up Saldo
              </button>

              <!-- Modal -->
              <div class="modal fade" id="topUpSaldo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h1 class="modal-title fs-5" id="exampleModalLabel">Top Up Saldo</h1>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('topUpSaldo') }}" method="POST">
                      @csrf
                      <div class="modal-body">
                        <div class="mb-3">
                          <label for="">Nominal</label>
                          <input type="number" class="form-control" name="nominal">
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>

              <!-- Button trigger modal -->
              <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#tarikTunai">
                Tarik Tunai
              </button>

              <!-- Modal -->
              <div class="modal fade" id="tarikTunai" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h1 class="modal-title fs-5" id="exampleModalLabel">Tarik Tunai</h1>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('tarikTunai') }}" method="POST">
                      @csrf
                      <div class="modal-body">
                        <div class="mb-3">
                          <label for="">Nominal</label>
                          <input type="number" class="form-control" name="nominal">
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
              </div>
              
            </div>
          </div>
          @endif
          @endauth
          <div class="card m-3 ms-0">
            <div class="card-header">
              Carts
            </div>
            <div class="card-body">
              @php
                $total = 0;
              @endphp
              @foreach ($carts as $cart)
              <div class="row">
                  <div class="col">
                    {{ $cart->product->name }}
                    <div class="d-flex">
                      <form class="d-flex" method="POST" action="{{ route('updateQuantity', $cart->id) }}">
                        @method('PUT')
                        @csrf
                        <input type="hidden" name="id" value="{{ $cart->id }}">
                        <input class="form-control" name="quantity" type="number" min="1" value="{{ $cart->quantity }}">
                        <button class="btn btn-info" type="submit">E</button>
                      </form>
                      <a onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini dari keranjang?')" href="{{ route('destroyCart', $cart->id) }}" class="btn btn-danger">H</a>
                    </div>
                  </div>
                  <div class="col d-flex justify-content-end">
                    {{ $cart->quantity * $cart->product->price }}
                  </div>
                </div>
                @php
                  $total += $cart->quantity * $cart->product->price;
                  // $total = $total + $cart->quantity * $cart->product->price;
                @endphp
                @endforeach
            </div>
            <div class="card-footer">
              <div class="d-flex justify-content-end pb-2">
                Total: {{ $total }}
              </div>

              <div class="d-grid gap-2">
                <a href="{{ route('checkout') }}" class="btn btn-primary">Checkout</a>
              </div>
            </div>
          </div>
        </div>
      </div>
      

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>