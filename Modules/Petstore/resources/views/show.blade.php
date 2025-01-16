@extends('petstore::layouts.master')

@section('content')
    Nazwa: {{ $pet->name }}
    <br/>
    Kategoria: {{ $pet->category->name  }}
    <br/>
    Foto:
    <ul>
        @forelse($pet->photoUrls as $photo)
            <li>{{$photo}}</li>
        @empty
            Brak fotografi
        @endforelse
    </ul>
    Tagi:
    <ul>
        @forelse($pet->tags as $tag)
            <li>{{$tag->name}}</li>
        @empty
            Brak tagów
        @endforelse
    </ul>

    Status: {{ $pet->status }}

@endsection
