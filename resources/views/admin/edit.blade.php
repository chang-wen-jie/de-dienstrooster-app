<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="card">
                        <div class="card-header">
                            Medewerkergegevens aanpassen
                        </div>

                        <div class="card-body">
                            <form method="post" action="{{ route('users.update', $user->id) }}">
                                @csrf
                                <label><b>ID</b></label>
                                <input class="form-control" value="{{$user->id}}" style="font-weight: bold" readonly />

                                <label for="name">Naam</label>
                                <input type="text" name="name" class="form-control" value="{{$user->name}}" />

                                <input type="checkbox" name="active" value="1" {{$user->active ? 'checked="checked' : ''}}"/>
                                <label for="active">Actief</label>

                                <button type="submit" class="btn btn-block btn-success">Aanpassen</button>

                                <a href="{{ route('users.admin') }}" class="btn btn-block btn-danger">
                                    Annuleren
                                </a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
