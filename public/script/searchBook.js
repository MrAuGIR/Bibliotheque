window.onload = () =>{

    var xmlhttp = new XMLHttpRequest();
    let keywords = document.getElementById('keywords');

    document.getElementById('keywords').addEventListener('change', (e) =>{

        e.preventDefault();

        if(keywords.value.length >= 3){

            // On récupère l'url active
            const Url = new URL(window.location.href);

            //on recupère la valeur du select
            let selectValue = document.querySelector('select').value;

            //on recupère la valeur du checkbox
            let freeValue = document.getElementById('free').value;

            let url = `${Url}?q=${keywords.value}`;

            if(selectValue != 'intitle'){
                url = `${Url}?q=+${selectValue}:${keywords.value}`;
            }

            if(freeValue == true){
                url = url+'&filter=free-ebook';
            }

            console.log(url);

            fetch(url, {
                method: "GET",
                headers: {
                    "X-Requested-With": "XMLHttpRequest"
                }
            }).then(response => 
                response.json()
            ).then(data => {
                // On va chercher la zone de contenu
                const content = document.querySelector("#content");

                // On remplace le contenu
                content.innerHTML = data.content;

                // On met à jour l'url
               //history.pushState({}, null, url);
            }).catch(e => alert(e));

        }

    });


}

