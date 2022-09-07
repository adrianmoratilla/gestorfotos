var searchBox = document.getElementById("searchBox");
if (searchBox){
searchBox.onkeyup = function (e) {
  var term = this.value;
  var resultDropdown = document.getElementById("searchResultContainer");
  if (term.length > 3) {
    fetch('./components/search.php?term=' + term).then((response) => response.json()).then((data) => {
      console.log(data);
      resultDropdown.innerHTML = "";
      if (data.length > 0) {
        for (let index = 0; index < data.length; index++) {
          let a = document.createElement('a');
          a.classList.add('searchResult');
          a.href = 'hilo.php?id=' + data[index].publi_id;
          let separator = document.createElement('span');
          separator.className = "separator";

          let text = document.createElement('div');
          text.innerText = data[index].publi_titulo;
          a.appendChild(text);
          resultDropdown.appendChild(a);
          resultDropdown.appendChild(separator);
          let category = document.createElement('span');
          category.className = "searchCategory";
          category.innerText = data[index].tema_nombre;
          a.appendChild(category);
        }
      } else {
        let result = document.createElement('p');
        result.innerText = "No hay resultados";
        result.className = "searchResult";
        resultDropdown.appendChild(result);

      }
    }
    )
  }
}
}
