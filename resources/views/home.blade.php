@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h1>Slambook responses</h1>
                    <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Share with your friends</button>
                    <a href="whatsapp://send?text={{Request::getHost()}}/slambook/welcome/{{Crypt::encrypt(Auth::user()->id)}}" data-action="share/whatsapp/share"><img src="/storage/whatsapp.png" alt="whatsApp" height="50px"></a>
                </div>

                @if (count($responses) > 0)
                <div class="card-body">
                    <table class="table table-hover table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>E-mail</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($responses as $response)
                                <tr>
                                    <td><a href="">{{$response->name}}</a></td>
                                    <td>{{$response->email}}</td>
                                    <td><a href="slambook/response/{{$response->id}}" class="btn btn-sm btn-primary">View</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else 
                    <div class="text-center">
                        <span class="text-danger"> No responses yet</span>
                    </div>
                @endif
                <!-- Modal -->
                <div id="myModal" class="modal fade" role="dialog">
                    <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Shareable Link</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>                                
                            </div>

                            <div class="modal-body">
                                <strong class="text-info">Copy below link to share with your friends</strong>
                                <br>
                                <input type="text" id="link-to-share" name="link" class="w-75" value="http://{{Request::getHost()}}/slambook/welcome/{{Crypt::encrypt(Auth::user()->id)}}">
                                <button type="button" onClick="copyToClipboard('link-to-share');">Copy</button>
                            </div>

                            <div class="modal-footer">
                                <button type="button" data-dismiss="modal">Close</button>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="card-body">

                    
                </div>
            </div>
        </div>
    </div>
</div>

<script type="application/javascript">
    function copyToClipboard(id) {
        document.getElementById(id).select();
        document.execCommand('copy');
        alert('Copied to clipboard');
    }
</script>

@endsection
