@extends('petstore::layouts.master')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col"></div>
            <div class="col-6">
                <form method="POST" action="{{ route('petstore.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="name">Name*</label>
                        <input type="text" class="form-control" id="name" name="name">
                    </div>
                    <div class="form-group">
                        <label for="category">Category</label>
                        <input type="text" class="form-control" id="category" name="category">
                    </div>
                    <div class="form-group">
                        <label for="tags">Tags (separated by a comma)</label>
                        <input type="text" class="form-control" id="tags" name="tags">
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status">
                            @foreach($allowed_statuses as $allowed_status)
                                <option
                                    value="{{ $allowed_status->value }}">{{ $allowed_status->value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                    @error('name')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </form>

            </div>
            <div class="col"></div>
        </div>
    </div>
@endsection
