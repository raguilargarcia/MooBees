@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Reportes</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th style="color: #FFC30B;">ID</th>
                <th style="color: #FFC30B;">Usuario</th>
                <th style="color: #FFC30B;">Reseña ID</th>
                <th style="color: #FFC30B;">Razón</th>
                <th style="color: #FFC30B;">Fecha de Creación</th>
                <th style="color: #FFC30B;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reports as $report)
                <tr>
                    <td style="color: #FFC30B;">{{ $report->id }}</td>
                    <td style="color: #FFC30B;">{{ $report->user->name }}</td>
                    <td style="color: #FFC30B;">{{ $report->review_id }}</td>
                    <td style="color: #FFC30B;">{{ $report->reason }}</td>
                    <td style="color: #FFC30B;">{{ $report->created_at }}</td>
                    <td>
                        <form action="{{ route('reports.accept', $report->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-success">Aceptar</button>
                        </form>
                        <form action="{{ route('reports.delete', $report->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-danger">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
