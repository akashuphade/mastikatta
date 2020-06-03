@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="card col-md-8 card-custom">
            <div class="card-header text-center">Add Question</div>
            <div class="card-body">
                <form method="POST" action="/questions">

                    @csrf

                    <div class="form-group row">
                        <label for="question" class="col-md-4 col-form-label text-md-right">Question</label>
                        <div class="col-md-6">
                            <textarea class="form-control @error('question') is-invalid @enderror" name="question" id="question" rows=2></textarea>

                            @error('question')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="type" class="col-md-4 col-form-label text-md-right">Type</label>
                        <div class="col-md-6">
                            <select class="form-control @error('type') is-invalid @enderror" name="type" id="type">
                                <option value="text">Text</option>
                                <option value="longtext">Longtext</option>
                                <option value="date">Date</option>
                                <option value="email">E-mail</option>
                            </select>

                            @error('type')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror

                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="visible" class="col-md-4 col-form-label text-md-right">Visible</label>
                        <div class="col-md-6 mt-2">
                            
                            <input type="checkbox" style="width:25px; height:25px;" class="@error('visible') is-invalid @enderror" name="visible" id="visible" checked>
                            
                            @error('visible')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <div class="col-md-8 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                                Save
                            </button>
                        </div>
                    </div>    
                    
                </form>
            </div>
        </div>
    </div>
@endsection