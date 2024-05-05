
function removeApiBookFromBiblio(){
    let links = document.querySelectorAll("[js-link-remove]");

    for (let link of links){

        link.addEventListener('click',async function(e){
            e.preventDefault();

            let idBook = this.getAttribute('book-id');

            const data = await removeBook(idBook);

            removeHtmlElement('card-'+idBook);
        });
    }
}

function addApiBookToBiblio (){
    let links = document.querySelectorAll("[js-link-add]");

    for( let link of links){
        link.addEventListener('click', async function(e){
            e.preventDefault()

            const divResult = document.getElementById('query-result');
            const p = divResult.querySelector('p');

            let idBook = this.getAttribute('book-id');
            let result = await addBook(idBook);
            console.log(result)
        });
    }
}

const addBook = async (apiId) => {

    const res = await fetch("/book/add",{
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({id:apiId})
    })

    if (res.ok) {
        return await res.json();
    }
    return undefined;
}

const removeBook = async (id) => {

    const res = await fetch("/book/"+id,{
        method: "DELETE",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({id:id})
    })

    if (res.ok) {
        return await res.json();
    }
    return undefined;
}

const removeHtmlElement = (id) => {
    const el = document.getElementById(id);
    if (el !== undefined) {
        el.innerHTML = ''
    }
}

window.onload = () => {
    removeApiBookFromBiblio()
    addApiBookToBiblio()
}







