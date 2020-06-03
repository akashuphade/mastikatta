@extends('layouts.app')

@section('content')

    <div class="row justify-content-center">
        <div class="card col-md-10 card-custom">
            <div class="row card-header">
                <h2 class="col-md-10 text-info">Response</h2>
                <a href="/home" class="col-md-2 btn btn-primary">Go Back</a>
            </div>
            <table class="table table-striped">
                <tr>
                    <th>question</th>
                    <th>Answer</th>
                </tr>
            @foreach($response as $answer)
                <tr>
                    <td>Name</td>
                    <td>{{$answer->name}}</td>
                </tr>
                <tr>
                    <td>E-mail</td>
                    <td>{{$answer->email}}</td>
                </tr>
                @foreach($answer->details as $question)
                    <tr>
                        <td>{{$question->question->description}}</td>
                        <td>{{$question->answer}}</td>
                    </tr>
                @endforeach
            @endforeach
            </table>
        </div>

    </div>

@endsection