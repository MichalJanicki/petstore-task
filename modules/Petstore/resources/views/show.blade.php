@extends('petstore::layouts.master')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col"></div>
            <div class="col-6">
                <h1>{{ $pet->name }}</h1>
                <p>Category: {{ $pet->category->name  }}</p>
                Poto:
                <ul>
                    @forelse($pet->photoUrls as $photo)
                        <li>{{$photo}}</li>
                    @empty
                        Brak fotografi
                    @endforelse
                </ul>
                Tags:
                <ul>
                    @forelse($pet->tags as $tag)
                        <li>{{$tag->name}}</li>
                    @empty
                        Brak tagów
                    @endforelse
                </ul>

                <p>Status: {{ $pet->status }}</p>
                <a class="btn btn-primary my-1"
                   href="{{ route('petstore.index', ['status' => $pet->status]) }}">Back</a>
            </div>
            <div class="col"></div>
        </div>
    </div>

@endsection
