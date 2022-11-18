@extends('layout')

@section('content')
    <div class="container">
        <div class="d-flex flex-column min-vh-100 justify-content-center">
            <div class="card">
                <div class="card-header">
                    <h3>Fertiliser Inventory</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('inventory.store') }}" method="post">
                        @csrf
                        <div class="form-group hstack gap-3">
                            <input class="form-control" type="number" placeholder="Input requested quantity..."
                                aria-label="Input requested quantity..." name="quantity" required>
                            <button class="btn btn-primary" type="submit">Request</button>
                        </div>
                    </form>
                    <br>
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            <h5 class="alert-heading">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                    <path
                                        d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                                </svg>
                                {{ session('success') }}
                            </h5>
                            <hr>
                            <p class="mb-0">
                                Applied <strong>{{ session('quantity') }} units </strong> at
                                <strong>{{ session('price') }}</strong>
                            </p>
                            <hr>
                            <p class="mb-0">
                                <strong>Remaining quantity on hand: </strong> {{ session('available') }}
                            </p>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            <h5 class="alert-heading">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                                    <path
                                        d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z" />
                                </svg>
                                {{ session('error') }}
                            </h5>
                            <hr>
                            <p class="mb-0">
                                Requested <strong>{{ session('quantity') }} units </strong> exceeds the quantity on hand
                            </p>
                            <hr>
                            <p class="mb-0">
                                <strong>Remaining quantity on hand: </strong> {{ session('available') }}
                            </p>
                        </div>
                    @endif

                    @if (session('msg'))
                        <div class="alert alert-danger" role="alert">
                            <h5 class="alert-heading">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                                    <path
                                        d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z" />
                                </svg>
                                {{ session('msg') }}
                            </h5>
                            <hr>
                            <p class="mb-0">
                                Please kindly try again. Requested qty must be > 0
                            </p>
                            <hr>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
@endsection
