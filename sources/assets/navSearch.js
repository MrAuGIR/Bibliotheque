let navSearch = () => {

    let input = document.querySelector('.js-search');
    let timeout = null

    input.addEventListener('input', (e)=>{
        e.preventDefault();

        clearTimeout(timeout);

        timeout = setTimeout(function () {
            if(input.value.length > 2){

                // On lance la requête ajax
                fetch('/search', {
                    method: "POST",
                    headers: {
                        "X-Requested-With": "XMLHttpRequest",
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        q: input.value
                    })
                }).then(response =>
                    response.json()
                ).then(data => {
                    console.log(data)
                    makeResult(data.result);

                }).catch(e => alert(e));
            }
        }, 1000);
    })
}

let makeResult = (books) =>{

    let div = document.getElementById('div-result');

    div.innerHTML = books;

    div.classList.add('js-result-display');
}

displayNone = () =>{

    document.querySelector('body').addEventListener('click', (e)=>{
        let div = document.getElementById('div-result');
        if(div.classList.contains('js-result-display')){
            div.innerHTML = "";
            div.classList.remove('js-result-display');
        }

    })
}

navSearch();
displayNone();