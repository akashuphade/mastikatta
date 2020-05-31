@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="card col-md-8">
            <div class="card-header">
                <h2><strong>Welcome to Mastikatta!!</strong></h2>
                <span class="text-success">Please fill below information to proceed</span>
            </div>
            <div class="card-body">
                <form method="post" action="/slambook/store">
                    {{ csrf_field() }}

                    <div class="form-group row">
                        <label for="name" class="col-form-label col-md-4 text-md-right">Name</label>
                        <div class="col-md-6">
                            <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Enter name">
                            
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }} </strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="email" class="col-form-label col-md-4 text-md-right">E-mail</label>
                        <div class="col-md-6">
                            <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Enter e-mail">
                            
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }} </strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row justify-content-center">
                        <button type="submit" class="btn btn-primary">Continue</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection