@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="card col-md-8 card-custom">
            <div class="card-header font-weight-bolder"><h3>Slambook of <strong class="text-info">{{ session('userName') }}</strong></h3></div>
            <div class="card-body">
                <form method="post" action="/slambook/store">
                    {{ csrf_field() }}

                    <div class="form-group row">
                        <label for="name" class="col-form-label col-md-4 text-md-right">Name</label>
                        <div class="col-md-6">
                            <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Enter name" value="{{old('name')}}">
                            
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
                            <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Enter e-mail" value="{{old('email')}}">
                            
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