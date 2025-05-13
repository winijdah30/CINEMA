@extends('template')

@section('title') 
    📋 Mes Réservations
@endsection

@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- En-tête -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                    <span class="bg-blue-600 text-white p-3 rounded-full shadow-lg">
                        <i class="fas fa-calendar-check"></i>
                    </span>
                    Mes Réservations
                </h1>
                <p class="text-gray-600 mt-2">Historique complet de vos commandes</p>
            </div>
            <a href="{{ route('home') }}" class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 transition-colors duration-200 text-gray-700">
                <i class="fas fa-arrow-left"></i>
                Retour à l'accueil
            </a>
        </div>

        <!-- Contenu principal -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200">
            @if($orders->isEmpty())
                <!-- Aucune commande -->
                <div class="p-8 text-center">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-yellow-100 mb-4">
                        <i class="fas fa-exclamation text-yellow-500 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune réservation trouvée</h3>
                    <p class="text-gray-500 mb-4">Vous n'avez effectué aucune commande pour le moment.</p>
                    <a href="{{ route('home') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                        <i class="fas fa-shopping-cart mr-2"></i>
                        Faire une réservation
                    </a>
                </div>
            @else
                <!-- Liste des commandes -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">N°</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date de réservation</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($orders as $order)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    #{{ $loop->iteration }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($order->delivery_date)->isoFormat('LL') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if($order->status == 'payé') bg-green-100 text-green-800
                                        @elseif($order->status == 'En cours') bg-yellow-100 text-yellow-800
                                        @else bg-red-100 text-red-800 
                                        @endif">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('orders.show', $order->id) }}" class="text-blue-600 hover:text-blue-900 transition-colors duration-200 p-2 rounded-full hover:bg-blue-50" title="Voir détails">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @can('update', $order)
                                        <a href="{{ route('orders.edit', $order->id) }}" class="text-yellow-600 hover:text-yellow-900 transition-colors duration-200 p-2 rounded-full hover:bg-yellow-50" title="Modifier">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        @endcan
                                        @can('delete', $order)
                                        <form action="{{ route('orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette commande ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 transition-colors duration-200 p-2 rounded-full hover:bg-red-50" title="Supprimer">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination (si nécessaire) -->
                @if($orders->hasPages())
                <div class="bg-gray-50 px-6 py-3 border-t border-gray-200">
                    {{ $orders->links() }}
                </div>
                @endif
            @endif
        </div>
    </div>
</div>

<!-- Script pour les tooltips -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const buttons = document.querySelectorAll('[title]');
        buttons.forEach(button => {
            new bootstrap.Tooltip(button);
        });
    });
</script>
@endsection