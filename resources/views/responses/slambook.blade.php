@extends('layouts.app')

@section('content')

    <div class="row justify-content-center">
        <div class="card col-md-8 card-custom">
            <div class="card-header font-weight-bolder"><h3>Slambook of <strong class="text-info">{{ session('userName') }}</strong></h3></div>
            <div class="card-body">
            @if (count($questions) > 0)
                <form id="slambook" method="POST" action="/slambook/navigateSlambook/next" enctype="multipart/form-data">

                {{ csrf_field() }}
                    
                    @foreach($questions as $question)
                        <div class="form-group row">
                            <label for="{{ $question->id }}" class="col-form-label col-md-4 text-md-right">{{$question->description}}</label>

                            <div class="col-md-6">
                                @if ($question->type == 'longtext')
                                    <textarea class="form-control @error('q_'.$question->id) is-invalid @enderror" id="q_{{$question->id}}" name="q_{{$question->id}}" rows=2></textarea>
                                @else 
                                    <input type="{{$question->type}}" class="form-control @error('q_'.$question->id) is-invalid @enderror" id="q_{{$question->id}}" name="q_{{$question->id}}" value="{{ old('q_'.$question->id) }}">
                                @endif

                                @error('q_'.$question->id)
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror 
                            </div>    
                        </div>
                    @endforeach
                    </hr>

                    <div class="row justify-content-center">
                    @if (session('currentPage') > 1)
                        <!--<button type="button" class="btn btn-primary" onClick="goToPrevious();">Previous</button> -->
                    @endif
                    @if (session('currentPage') < session('totalPages'))
                        <button type="submit" class="btn btn-primary ml-1">Next</button>
                    @endif
                    @if (session('currentPage') == session('totalPages'))
                        <div class="ml-1">        
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>    
                    @endif
                    </div>
                </form>
            @else
                <div class="text-center bg-success rounded">
                    <h3 class="text-white">Thank you for taking time to fill my slambook</h3>
                </div>
            @endif
            </div>
        </div>
    </div>

    <script type="application/javascript">

        function goToNext() {
            var form = document.getElementById('slambook');
            form.action = "/slambook/navigateSlambook/next";
            form.submit();
        }

    </script>
@endsection