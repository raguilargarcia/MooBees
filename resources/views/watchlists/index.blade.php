@extends('layouts.app')

@section('content')
<div class="container watchlists-page">
    <h1>Crear Watchlists</h1>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <form action="{{ route('watchlists.store') }}" method="POST" class="create-watchlist-form">
        @csrf
        <div class="form-group">
            <label for="watchlist_name">Nombre de la Watchlist</label>
            <input type="text" class="form-control" id="watchlist_name" name="name" required>
        </div>
        <button type="submit" class="btn btn-primary">Crear Watchlist</button>
    </form>
</div>

<div class="container watchlists-page">
    <h1>Mis Watchlists</h1>

    <div class="watchlists-container">
        <ul class="list-group mt-4">
            @foreach ($watchlists as $watchlist)
            <li class="list-group-item d-flex align-items-center position-relative">
                <div class="watchlist-title w-100">
                    <a href="{{ route('watchlists.show', $watchlist->id) }}" class="full-width-link">{{ $watchlist->name }}</a>
                </div>
                <form action="{{ route('watchlists.destroy', $watchlist->id) }}" method="POST" class="delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-link p-0" onclick="return confirmDelete(event);"><i class="fas fa-times text-danger"></i></button>
                </form>
            </li>
            @endforeach
        </ul>
    </div>
</div>

<script>
function confirmDelete(event) {
    event.preventDefault();
    const form = event.target.closest('form');
    Swal.fire({
        title: '¿Estás seguro?',
        text: "No podrás revertir esto",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminarlo',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
    return false;
}
</script>
@endsection
