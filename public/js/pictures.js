new StarRating('.star-rating');

const picturesContainer = document.getElementById('pictures');

/*Formulario*/
const stars = new StarRating('#rating');
const modalForm = new bootstrap.Modal(document.getElementById('modalForm'));
const modalTitle = document.getElementById('modalTitle');
const imgPreview = document.getElementById('imgPreview');
const imageInputContainer = document.getElementById('imageInputContainer');
const picture = document.getElementById('picture');
const title = document.getElementById('title');
const rating = document.getElementById('rating');
const dateTaken = document.getElementById('dateTaken');
const errors = document.getElementById('errors');

var selectedId = -1;
const modalDelete = new bootstrap.Modal(document.getElementById('modalDelete'));

function openModal(mode, id) {
    //Modo nuevo
    if (mode == 'create') {
        imgPreview.src = "";
        imageInputContainer.style.display = 'flex';
        modalTitle.innerText = 'Nueva imagen';
        title.value = "";
        rating.value = '0';
        dateTaken.value = "";
        selectedId = -1;
    }
    //Modo edición
    else {

        selectedId = id;
        imgPreview.src = pictures[id].image;
        imageInputContainer.style.display = 'none';
        modalTitle.innerText = 'Editar imagen';
        title.value = pictures[id].title;
        rating.value = pictures[id].rating;
        dateTaken.value = pictures[id].dateTaken;
    }

    picture.value = "";
    errors.innerHTML = "";
    errors.style.display = 'none';
    stars.rebuild();

    modalForm.show();
}

function previewPicture() {
    var files = picture.files;
    if (files.length > 0) {
        getBase64(files[0]);
    }
};

function getBase64(file) {
    var reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = function () {
        imgPreview.src = reader.result;
    };
    reader.onerror = function (error) {
        console.log('Error: ', error);
    };
}

function save() {
    sendData().then(readResponse);
}

async function sendData() {
    let formData = new FormData();
    formData.append("image", picture.files[0]);
    formData.append("title", title.value);
    formData.append("rating", rating.value);
    formData.append("dateTaken", dateTaken.value);
    if (selectedId > 0)
        formData.append("id", selectedId);
    var response = await fetch(postUrl, {
        method: "POST",
        body: formData,
        credentials: 'include',
        headers: {
            'X-CSRF-TOKEN': csrf
        }
    });
    return response.json();
}

function readResponse(data) {

    //Comprobar el status

    //Si es bueno
    if (data.status == 20) {
        //Se almacena la foto

        //Si era nuevo, se añade a la lista
        if (selectedId < 0) {
            var picture = {
                "title": title.value,
                "image": imgPreview.src,
                "rating": rating.value,
                "dateTaken": dateTaken.value,
                "createDate": new Date().toISOString().slice(0, 10)
            };
            pictures[data.msg] = picture;
            addCard(data.msg, picture);
        }
        //Si era edición, se reemplaza
        else {
            pictures[selectedId].title = title.value;
            pictures[selectedId].rating = rating.value;
            pictures[selectedId].dateTaken = dateTaken.value;

            document.getElementById('cardTitle' + selectedId).innerText = title.value;
            document.getElementById('cardDate' + selectedId).innerText = dateTaken.value;

            let starsContainer = document.getElementById('starsContainer' + selectedId);
            starsContainer.innerHTML = "";
            starsContainer.appendChild(generateCardRating(selectedId, rating.value));
            new StarRating('#cardRating' + selectedId);



            selectedId = -1;
        }
        //Se cierra el formulario
        modalForm.hide();

    }
    //Si es malo, se muestra alerta
    else {
        for (let msg of data.msg) {
            let p = document.createElement('p');
            p.innerText = msg;
            errors.appendChild(p);
        }
        errors.style.display = 'block';
    }

}

function addCard(id, values) {

    let card = document.createElement('div');
    card.classList.add('card');
    card.classList.add('col-md-4');
    card.classList.add('col-sm-6');
    card.classList.add('col-12');
    card.id = "img-" + id;
    let img = document.createElement('img');
    img.src = values.image;
    img.classList.add('card-img-top');
    card.appendChild(img);
    let cardBody = document.createElement('div');
    cardBody.classList.add('card-body');
    cardBody.id = "cardBody" + id;
    card.appendChild(cardBody);
    let title = document.createElement('h5');
    title.id = "cardTitle" + id;
    title.innerText = values.title;
    cardBody.appendChild(title);
    let dateTaken = document.createElement('p');
    dateTaken.id = "cardDate" + id;
    dateTaken.innerText = "Fecha de captura: " + values.dateTaken;
    cardBody.appendChild(dateTaken);
    let createDate = document.createElement('p');
    createDate.id = "createDate" + id;
    createDate.innerText = "Fecha de subida: " + values.createDate;
    cardBody.appendChild(createDate);
    let starsContainer = document.createElement('div');
    starsContainer.id = "starsContainer" + id;
    cardBody.appendChild(starsContainer);


    starsContainer.appendChild(generateCardRating(id, values.rating));


    let row = document.createElement('div');
    row.classList.add('row');
    row.classList.add('mt-3');

    cardBody.appendChild(row);

    let buttonEdit = document.createElement('button');
    buttonEdit.onclick = function () { openModal('edit', id) };
    buttonEdit.classList.add('btn');
    buttonEdit.classList.add('btn-primary');
    buttonEdit.classList.add('col-md-5');
    buttonEdit.classList.add('col-sm-6');
    buttonEdit.classList.add('col-12');
    buttonEdit.innerText = "Editar";
    row.appendChild(buttonEdit);

    let buttonDelete = document.createElement('button');
    buttonDelete.onclick = function () { confirmDeletion(id) };
    buttonDelete.classList.add('btn');
    buttonDelete.classList.add('btn-danger');
    buttonDelete.classList.add('col-md-5');
    buttonDelete.classList.add('col-sm-6');
    buttonDelete.classList.add('col-12');
    buttonDelete.classList.add('offset-md-2');
    buttonDelete.innerText = "Borrar";
    row.appendChild(buttonDelete);

    if (Object.keys(pictures).length == 1)
        picturesContainer.innerHTML = "";
    picturesContainer.prepend(card);

    new StarRating('#cardRating' + id);
}

function generateCardRating(id, rating) {
    let cardRating = document.createElement('select');
    cardRating.id = "cardRating" + id;
    let option0 = document.createElement('option');
    cardRating.appendChild(option0);
    let option1 = document.createElement('option');
    option1.value = '1';
    if (rating == '1')
        option1.selected = true;
    cardRating.appendChild(option1);
    let option2 = document.createElement('option');
    option2.value = '2';
    if (rating == '2')
        option2.selected = true;
    cardRating.appendChild(option2);
    let option3 = document.createElement('option');
    option3.value = '3';
    if (rating == '3')
        option3.selected = true;
    cardRating.appendChild(option3);
    let option4 = document.createElement('option');
    option4.value = '4';
    if (rating == '4')
        option4.selected = true;
    cardRating.appendChild(option4);
    let option5 = document.createElement('option');
    option5.value = '5';
    if (rating == '5')
        option5.selected = true;
    cardRating.appendChild(option5);
    cardRating.disabled = true;

    return cardRating;
}

function confirmDeletion(id) {
    selectedId = id;
    modalDelete.show();
}

async function removePicture() {
    //Enviar la solicitud de borrado
    await fetch(deleteUrl, {
        method: "DELETE",
        body: selectedId,
        credentials: 'include',
        headers: {
            'X-CSRF-TOKEN': csrf
        }
    });
    //Ocultar el formulario
    modalDelete.hide();
    //Borrar la foto de la lista
    delete pictures[selectedId];
    document.getElementById('img-' + selectedId).remove();
    selectedId = -1;
}

async function orderPictures(value) {
    let startDate = document.getElementById("startDate").value;
    let endDate = document.getElementById("endDate").value+" 23:59:59";
    console.log(endDate);
    await fetch(orderUrl + "?sort=" + value + "&order=" + order + "&startDate=" + startDate + "&endDate=" + endDate, {
        method: "GET",
        credentials: 'include',
        headers: {
            'X-CSRF-TOKEN': csrf
        }
    }).then((response) => response.json())
        .then((data) => {
            picturesContainer.innerHTML = "";
            pictures = {};
            for (let i = 0; i < data.length; i++) {
                pictures[data[i].id] = {
                    "title": data[i].picture_name,
                    "image": window.location.href + "picture/" + data[i].picture_url,
                    "rating": data[i].rating,
                    "dateTaken": data[i].date_taken,
                    "createDate": data[i].created_at.substring(0, 10)
                };

                addCard(data[i].id, pictures[data[i].id])
            }
        }
        );
}

function changeOrder() {
    let button = document.getElementById("orderButton");
    button.innerText = order === "ASC" ? "Ascendente" : "Descendente";
    order = order === "ASC" ? "DESC" : "ASC";
    orderPictures(document.getElementById("inputGroupSelect04").value)
}

async function searchPictures(searchValue) {

    await fetch(searchUrl + "?search=" + searchValue, {
        method: "GET",
        credentials: 'include',
        headers: {
            'X-CSRF-TOKEN': csrf
        }
    }).then((response) => response.json())
        .then((data) => {
            picturesContainer.innerHTML = "";
            if (data.length > 0) {
                console.log(data.length);
                pictures = {};

                for (let i = 0; i < data.length; i++) {
                    pictures[data[i].id] = {
                        "title": data[i].picture_name,
                        "image": window.location.href + "picture/" + data[i].picture_url,
                        "rating": data[i].rating,
                        "dateTaken": data[i].date_taken,
                        "createDate": data[i].created_at.substring(0, 10)
                    };

                    addCard(data[i].id, pictures[data[i].id])
                }
            } else {
                picturesContainer.innerHTML = '<h2 style="text-align:center;">No se encuentra ninguna foto con ese título</h2>';
            }

        });
}


function showPicture(ev){
    let previousPicture = document.getElementById('pictures').lastElementChild;
    let nextPicture = document.getElementById('pictures').firstElementChild;
    let imgSrc = "";

    if (!ev.target.dataset.id){
        imgSrc = ev.target.src;
        previousPicture = ev.target.parentNode.previousElementSibling || previousPicture;
        nextPicture = ev.target.parentNode.nextElementSibling || nextPicture;
    } else {
        imgSrc = document.getElementById(ev.target.dataset.id).firstElementChild.src;
        previousPicture = document.getElementById(ev.target.dataset.id).previousElementSibling  || previousPicture;
        nextPicture = document.getElementById(ev.target.dataset.id).nextElementSibling || nextPicture;
    }

    document.getElementById('modalPicture').style.display = "block";
    document.getElementById('modalImg').src = imgSrc;
    document.getElementById('previousButton').dataset.id = previousPicture.id;
    document.getElementById('nextButton').dataset.id = nextPicture.id;
}


function showFilter() {
    let filter = document.getElementById('filterContainer')

    filter.style.display = filter.style.display == "none" ? "flex" : "none"
}

function closeModal(){
    document.getElementById('modalPicture').style.display = "none";
}
