@extends('petstore::layouts.master')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col"></div>
            <div class="col-6">
                <form method="POST" action="{{ route('petstore.update', ['id' => $pet->id]) }}">
                    @csrf
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name"
                               value="{{$pet->name}}">
                    </div>
                    <div class="form-group">
                        <label for="category">Category</label>
                        <input type="text" class="form-control" id="category" name="category"
                               value="{{ $pet->category->name }}">
                    </div>
                    <div class="form-group">
                        <label for="tags">Tags (separated by a comma)</label>
                        <input type="text" class="form-control" id="tags" name="tags"
                               value="{{ $pet->getTagsAsString() }}">
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status">
                            @foreach($allowed_statuses as $allowed_status)
                                <option
                                        @if($pet?->status === $allowed_status->value) selected @endif
                                value="{{ $allowed_status->value }}">{{ $allowed_status->value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <a class="btn btn-primary my-1"
                       href="{{ route('petstore.index', ['status' => $pet->status]) }}">Back</a>
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
