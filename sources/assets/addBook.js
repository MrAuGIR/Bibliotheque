
function removeApiBookFromBiblio() {
  let links = document.querySelectorAll("[js-link-remove]");

  for (let link of links) {

    link.addEventListener('click', async function(e) {
      e.preventDefault();

      let idBook = this.getAttribute('book-id');

      const data = await removeBook(idBook);

      removeHtmlElement('card-' + idBook);
      if (data !== undefined) {

        link.removeAttribute('js-link-remove');
        link.setAttribute('js-link-add', '');
        link.classList.remove('remove-book');
        link.classList.add('add-book');

        if (data.api_id) {
          link.setAttribute('book-id', data.api_id);
        }

        const iconElement = link.querySelector('i');
        if (iconElement) {
          iconElement.className = 'icon-button fas fa-plus fa-2x';
        }

        const textSpan = link.querySelector('span');
        if (textSpan) {
          textSpan.innerText = 'Ajouter a la bilbio';
        }
        link.replaceWith(link.cloneNode(true));
        addApiBookToBiblio();
        document.dispatchEvent(new Event('DOMContentLoaded'));
      }
    });
  }
}

function addApiBookToBiblio() {
  let links = document.querySelectorAll("[js-link-add]");

  for (let link of links) {
    link.addEventListener('click', async function(e) {
      e.preventDefault()

      const divResult = document.getElementById('query-result');

      let idBook = this.getAttribute('book-id');
      let res = await addBook(idBook);

      if (res !== undefined) {
        link.removeAttribute('js-link-add');
        link.setAttribute('js-link-remove', '');
        link.classList.remove('add-book');
        link.classList.add('remove-book');

        if (res.id) {
          link.setAttribute('book-id', res.id);
        }

        const iconElement = link.querySelector('i');
        if (iconElement) {
          iconElement.className = 'icon-button fas fa-minus-square fa-2x';
        }

        const textSpan = link.querySelector('span');
        if (textSpan) {
          textSpan.innerText = 'Supprimer de ma biblio';
        }
        link.replaceWith(link.cloneNode(true));
        removeApiBookFromBiblio();
        document.dispatchEvent(new Event('DOMContentLoaded'))
      }
    });
  }
}

const addBook = async (apiId) => {

  const res = await fetch("/book/add", {
    method: "POST",
    headers: {
      "Content-Type": "application/json"
    },
    body: JSON.stringify({ id: apiId })
  })

  if (res.ok) {
    return await res.json();
  }
  return undefined;
}

const removeBook = async (id) => {

  const res = await fetch("/book/" + id, {
    method: "DELETE",
    headers: {
      "Content-Type": "application/json"
    },
    body: JSON.stringify({ id: id })
  })

  if (res.ok) {
    return await res.json();
  }
  return undefined;
}

const removeHtmlElement = (id) => {
  const el = document.getElementById(id);
  console.log(el);
  if (el) {
    el.innerHTML = ''
  }
}

window.onload = () => {
  removeApiBookFromBiblio()
  addApiBookToBiblio()
}







