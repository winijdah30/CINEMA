<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Création d'un Film</title>
</head>
<body>

<div class="card-body">
    <!-- Affichage des erreurs de validation -->
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Formulaire de création/édition -->
    <form action="{{ $action }}" method="POST">
        @csrf
        @isset($movie)
            @method('PUT')  <!-- Si l'élément existe, on envoie une requête PUT -->
        @endisset

        <!-- Champ Nom du Film -->
        <div class="mb-3 row">
            <label for="name" class="col-sm-2 col-form-label">Nom du Film</label>
            <div class="col-sm-10">
                <input 
                    type="text" 
                    class="form-control" 
                    name="name" 
                    id="name" 
                    placeholder="Saisir le nom du film" 
                    value="{{ old('name', $movie->name ?? '') }}" 
                    autofocus 
                />
            </div>
        </div>

        <!-- Champ Description du Film -->
        <div class="mb-3 row">
            <label for="description" class="col-sm-2 col-form-label">Description</label>
            <div class="col-sm-10">
                <textarea 
                    class="form-control" 
                    id="description" 
                    name="description" 
                    rows="3" 
                    placeholder="Saisir la description du film">{{ old('description', $movie->description ?? '') }}</textarea>
            </div>
        </div>

        <!-- Champ Durée du Film -->
        <div class="mb-3 row">
            <label for="duration" class="col-sm-2 col-form-label">Durée (en minutes)</label>
            <div class="col-sm-10">
                <input 
                    type="number" 
                    class="form-control" 
                    name="duration" 
                    id="duration" 
                    placeholder="Saisir la durée du film" 
                    value="{{ old('duration', $movie->duration ?? '') }}" 
                    min="0" 
                />
            </div>
        </div>

        <!-- Champ Date de Sortie -->
        <div class="mb-3 row">
            <label for="date" class="col-sm-2 col-form-label">Date de Sortie</label>
            <div class="col-sm-10">
                <input 
                    type="date" 
                    class="form-control" 
                    name="date" 
                    id="date" 
                    value="{{ old('date', $movie->date ?? '') }}" 
                />
            </div>
        </div>

        <!-- Sélecteur de Catégorie(s) -->
        <div class="mb-3 row">
            <label for="select_categories" class="col-sm-2 col-form-label">Catégorie(s)</label>
            <div class="col-sm-10">
                <select id="select_categories" class="form-select" onchange="categories.select()">
                    <option value="-1">Sélectionnez une catégorie</option>
                    @foreach($categories as $category)
                        <option value="{{$category->id}}">{{$category->name}}</option>
                    @endforeach
                </select>
                <span id="categoriesList"></span> <!-- Affichage dynamique des catégories sélectionnées -->
            </div>
        </div>

        <!-- Boutons de soumission -->
        <div class="mb-3">
            <div class="offset-sm-2 col-sm-10">
                <button class="btn btn-primary mb-1 mr-1" type="submit">Valider</button>
                <a href="{{ $cancelRoute }}" class="btn btn-danger mb-1">Annuler</a>
            </div>
        </div>
    </form>
</div>

</body>
</html>