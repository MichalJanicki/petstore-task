@extends('petstore::layouts.master')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col"></div>
            <div class="col-6">
                <form method="GET" action="{{ route('petstore.index') }}">
                    <div class="form-group">
                        <label for="status">Status:</label>
                        <select name="status" class="form-control" id="status">
                            @foreach($allowed_statuses as $allowed_status)
                                <option
                                    @if($allowed_status->value === $current_status) selected
                                    @endif value="{{$allowed_status->value}}">{{$allowed_status->value}}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary my-1">Submit</button>
                    @error('status')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </form>
                @if (session('success'))
                    <div class="alert alert-success"> {{ session('success') }} </div>
                @endif @if (session('error'))
                    <div class="alert alert-danger"> {{ session('error') }} </div>
                @endif
            </div>
            <div class="col">
                <a class="btn btn-success my-1" href="{{ route('petstore.create')  }}">Add new pet</a>
            </div>
        </div>
        <div class="row">
            <div class="col"></div>
        </div>
        <div class="row">
            <div class="col"></div>
            <div class="col-6">
                <table class="table">
                    <thead class="thead-dark">
                    <tr>
                        <td>Id</td>
                        <td>Name</td>
                        <td>Action</td>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($pets as $pet)
                        <tr>
                            <td>{{$pet->id}}</td>
                            <td>{{$pet->name}}</td>
                            <td>
                                <a class="btn btn-primary"
                                   href="{{ route('petstore.edit', ['id' => $pet->id])  }}">Edit</a>
                                <button class="btn btn-danger remove-pet"
                                        type="button"
                                        data-pet-url="{{ route('petstore.destroy', ['id' => $pet->id]) }}">Remove
                                </button>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">Brak rekordów</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="col"></div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        const buttons = document.querySelectorAll(".remove-pet");

        buttons.forEach((button) => {
            button.addEventListener("click", function () {
                const url = button.getAttribute("data-pet-url");
                const userConfirmed = window.confirm('Are you sure you want to delete this pet?');
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                if (userConfirmed) {
                    fetch(`${url}`, {
                        method: "DELETE",
                        headers: {
                            "Content-Type": "application/json",
                            'X-CSRF-TOKEN': token
                        },
                    })
                        .then((response) => {
                            if (!response.ok) {
                                throw new Error("Network response was not ok " + response.statusText);
                            }
                            return response.json();
                        })
                        .then((data) => {
                            alert(data);
                            window.location.reload();
                        })
                        .catch((error) => {
                            alert(error);
                            window.location.reload();
                        });
                }
            });
        });
    </script>
@endsection
