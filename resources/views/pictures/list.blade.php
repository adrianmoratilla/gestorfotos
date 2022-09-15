@extends('layout')

@section('content')

    <div class="row mb-5" style="display:flex; justify-content: space-between">
        <div class="input-group" style="width:40%">
            <div class="input-group-prepend">
                <label class="input-group-text" for="inputGroupSelect04" onclick="showFilter()"><i class="bi bi-funnel-fill"></i></label>
            </div>
            <div id="filterContainer">
            <select class="custom-select" id="inputGroupSelect04" onchange="orderPictures(this.value)">
                <option value="picture_name">Alfabético</option>
                <option value="rating">Calificación</option>
                <option value="date_taken">Fecha de captura</option>
                <option value="created_at" selected>Fecha de subida</option>
            </select>
            <div class="input-group-append">
                <button id="orderButton" class="btn btn-outline-secondary" onclick="changeOrder()">Ascendente</button>
            </div>
            </div>
        </div>
        <button id="newPicture" class="btn btn-success col-2" onclick="openModal('create')">Añadir</button>
    </div>

    <section id="pictures" class="row g-3 gap" style="margin-bottom: 2%">
        @if (!count($pictures))
            <h2>¡Añade tus primeras fotos!</h2>
        @else
            @foreach ($pictures as $picture)
                <div class="card col-md-4 col-sm-6 col-12" id="img-{{ $picture->id }}">
                    <img src ={{ route('get-picture', ['picture' => $picture->picture_url]) }} class="card-img-top">
                    <div class="card-body">
                        <h5 id="cardTitle{{ $picture->id }}">{{ $picture->picture_name }}</h5>
                        <p id="cardDate{{ $picture->id }}">Fecha de captura: {{ $picture->date_taken }}</p>
                        <p id="createDate{{ $picture->id }}">Fecha de subida: {{ $picture->created_at->format("Y-m-d")}}</p>
                        <div id="starsContainer{{ $picture->id }}">
                            <select class="star-rating" id="cardRating{{ $picture->id }}" disabled="">
                                <option></option>
                                <option value="1" @selected('1' == $picture->rating)></option>
                                <option value="2" @selected('2' == $picture->rating)></option>
                                <option value="3" @selected('3' == $picture->rating)></option>
                                <option value="4" @selected('4' == $picture->rating)></option>
                                <option value="5" @selected('5' == $picture->rating)></option>
                            </select>
                        </div>
                        <div class="row mt-3">
                            <button class="btn btn-primary col-md-5 col-sm-6 col-12"
                            onclick="openModal('edit',{{ $picture->id }})">Editar</button>
                            <button class="btn btn-danger col-md-5 col-sm-6 col-12 offset-md-2"
                            onclick="confirmDeletion({{ $picture->id }})">Borrar</button>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </section>

    <div id="modalForm" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-4">
                        <img id="imgPreview" class="col-10 offset-1">
                    </div>
                    <div class="row mb-3" id="imageInputContainer">
                        <label for="picture" class="col-sm-2 col-form-label">Imagen</label>
                        <div class="col-sm-10">
                            <input type="file" accept="image/*" class="form-control" id="picture"
                                onchange="previewPicture()">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="title" class="col-sm-2 col-form-label">Título</label>
                        <div class="col-sm-10">
                            <input type="text" maxlength="80" class="form-control" id="title">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="rating" class="col-sm-2 col-form-label">Calificación</label>
                        <div class="col-sm-10">
                            <select id="rating">
                                <option value="0"></option>
                                <option value="1"></option>
                                <option value="2"></option>
                                <option value="3"></option>
                                <option value="4"></option>
                                <option value="5"></option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="userDate" class="col-sm-2 col-form-label">Fecha de captura</label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control" id="dateTaken">
                        </div>
                    </div>
                    <div class="alert alert-danger" id="errors" style="display:none"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" onclick="save()">Guardar</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalDelete" tabindex="-1" aria-labelledby="modalDeleteLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDeleteLabel">¿Borrar la foto?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Si la borras, no podrás recuperarla</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" onclick="removePicture()">Borrar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('scripts')
    <script type="text/javascript" src="{{ asset('js/star-rating.js') }}"></script>

    {{-- Variables para el javascript --}}
    <script type="text/javascript">
        const postUrl = "{{ route('save-picture') }}";
        const deleteUrl = "{{ route('remove-picture') }}";
        const orderUrl = "{{ route('order-pictures') }}";
        const searchUrl = "{{ route('search-pictures') }}";
        const csrf = "{{ csrf_token() }}";
        var order = "ASC";
        var pictures = {
            @foreach ($pictures as $picture)
                "{{ $picture->id }}": {
                    "title": "{{ $picture->picture_name }}",
                    "image": "{{ route('get-picture', ['picture' => $picture->picture_url]) }}",
                    "rating": "{{ $picture->rating }}",
                    "dateTaken": "{{ $picture->date_taken }}",
                    "createDate": "{{ $picture->created_at->format("Y-m-d")}}"
                },
            @endforeach
        };

        function showFilter(){
            let filter = document.getElementById('filterContainer')

            filter.style.display = filter.style.display == "none" ? "flex" : "none"
        }
    </script>

    <script type="text/javascript" src="{{ asset('js/pictures.js') }}"></script>
@endsection
