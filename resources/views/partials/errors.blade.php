@if($errors->any())
    <div class="row justify-content-center">
        <div class="col-sm-12 col-md-4">
            <div class="alert alert-danger text-center" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{$error}} <i class="bi bi-exclamation-triangle"></i></li>
                    @endforeach 
                </ul>
            </div>
        </div>
    </div>
@endif