@extends('layout')

@section('content')

    <div class="buttonContainer mb-3">

        <button class="btn btn-secondary" type="button" id="orderSelect" onclick="showFilter()" title="Cambiar orden">
            Fecha de subida <i class="bi bi-caret-down"></i>
        </button>
        <div class="dropdown-menu" id="filterContainer">
            <option class="dropdown-item" type="button" value="picture_name" onclick="filterPictures(event.target)">Alfabético</option>
            <option class="dropdown-item" type="button" value="rating" onclick="filterPictures(event.target)">Calificación</option>
            <option class="dropdown-item" type="button" value="date_taken" onclick="filterPictures(event.target)">Fecha captura</option>
            {{-- <option class="dropdown-item" type="button" value="created_at" onclick="filterPictures(event.target)">Fecha de subida</option> --}}
        </div>

        <button id="orderButton" class="btn btn-outline-secondary" onclick="changeOrder(event.target)"
            title="Cambiar orden ascendente/descendente">
            <i class="bi bi-sort-down"></i>
        </button>

        <button id="orderButton" class="btn btn-outline-secondary" onclick="showDates()" title="Filtrar por fecha de captura">
            <i class="bi bi-funnel"></i>
        </button>

        <button id="newPicture" class="btn btn-success" onclick="openModal('create')" title="Añadir nueva foto">
            <i class="bi bi-plus-circle"></i>
        </button>
    </div>

    <div class="row mb-3" id="dateContainer" style="display:none">
        <div class="input-group" id="dateSelector">
            <div class="form-floating mb-3">
                <input type="date" id="startDate" class="form-control" onchange="filterPictures()" onkeydown="return false">
                <label for="startDate">Desde:</label>
            </div>

            <div class="form-floating mb-3">
                <input type="date" id="endDate" class="form-control" onchange="filterPictures()" onkeydown="return false">
                <label for="endDate">Hasta:</label>
            </div>
        </div>
        <div class="alert alert-danger" id="dateErrors" style="display:none"></div>
        </div>
    </div>

    <section id="pictures" class="row g-3 gap" style="margin-bottom: 2%">
        @if (!count($pictures))
            <h2>¡Añade tus primeras fotos!</h2>
        @else
            @foreach ($pictures as $picture)
                <div class="card col-md-4 col-sm-6 col-12" id="img-{{ $picture->id }}">
                    <img src={{ route('get-picture', ['picture' => $picture->picture_url]) }} class="card-img-top"
                        onclick="showPicture(event)">
                    <div class="card-body">
                        <h5 id="cardTitle{{ $picture->id }}">{{ $picture->picture_name }}</h5>
                        <p id="cardDate{{ $picture->id }}">Tomada el {{date('d-m-Y',$picture->date_taken)}}</p>
                        {{-- <p id="createDate{{ $picture->id }}">Fecha de subida: {{ $picture->created_at->format('Y-m-d') }}</p> --}}
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
    <div class="modal" id="modalPicture" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                onclick="closeModal()"></button>
            <div class="modal-body">
                <img id="modalImg">
                <h4 id="modalTitle"></h4>
            </div>
            <a class="carousel-control-prev" role="button">
                <span class="carousel-control-prev-icon" aria-hidden="true" id="previousButton"
                    onclick="showPicture(event)"></span>
            </a>
            <a class="carousel-control-next" role="button">
                <span class="carousel-control-next-icon" aria-hidden="true" id="nextButton"
                    onclick="showPicture(event)"></span>
            </a>
        </div>
    </div>
    <div id="modalForm" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
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
                    <button type="button" class="btn btn-danger" onclick="removePicture()">Borrar</button>
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
        const filterUrl = "{{ route('filter-pictures') }}";

        const csrf = "{{ csrf_token() }}";
        var order = "ASC";
        var pictures = {
            @foreach ($pictures as $picture)
                "{{ $picture->id }}": {
                    "title": "{{ $picture->picture_name }}",
                    "image": "{{ route('get-picture', ['picture' => $picture->picture_url]) }}",
                    "rating": "{{ $picture->rating }}",
                    "dateTaken": "{{ $picture->date_taken }}",
                    // "createDate": "{{ $picture->created_at->format('Y-m-d') }}"
                },
            @endforeach
        };

    </script>

    <script type="text/javascript" src="{{ asset('js/pictures.js') }}"></script>
@endsection
