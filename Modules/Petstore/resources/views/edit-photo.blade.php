@extends('petstore::layouts.master')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col"></div>
            <div class="col-6">
                <form method="POST" action="{{ route('petstore.updatePhoto', ['id' => $id]) }}"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="photo">Photo</label> <br/>
                        <input type="file" class="form-control-file" id="photo" name="photo">
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <a class="btn btn-primary my-1"
                       href="{{ route('petstore.index') }}">Back</a>
                </form>
                @if (session('success'))
                    <div class="alert alert-success"> {{ session('success') }} </div>
                @endif @if (session('error'))
                    <div class="alert alert-danger"> {{ session('error') }} </div>
                @endif
            </div>
            <div class="col"></div>
        </div>
    </div>
@endsection
