@extends('template')

@section('title', 'Mes reservation')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">📦 Mes Réservation</h2>
    
    @if($orders->isEmpty())
        <div class="alert alert-warning">Vous n'avez aucune commande pour le moment.</div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Adresse</th>
                        <th>Date de livraison</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $order->address }}</td>
                            <td>{{ \Carbon\Carbon::parse($order->delivery_date)->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge 
                                    @if($order->status == 'payé') bg-success
                                    @elseif($order->status == 'En cours') bg-warning text-dark
                                    @else bg-danger 
                                    @endif">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-info btn-sm">
                                    <i class="bi bi-eye"></i> Voir
                                </a>
                                @can('update', $order)
                                    <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-warning btn-sm">
                                        <i class="bi bi-pencil"></i> Modifier
                                    </a>
                                @endcan
                                @can('delete', $order)
                                    <form action="{{ route('orders.destroy', $order->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette commande ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="bi bi-trash"></i> Supprimer
                                        </button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
    <a href="{{ route('home') }}" class="btn btn-secondary mt-3"><i class="bi bi-arrow-left"></i> Retour</a>
</div>
@endsection
