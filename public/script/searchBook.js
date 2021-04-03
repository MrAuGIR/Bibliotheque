window.onload = () =>{

    var xmlhttp = new XMLHttpRequest();
    let keywords = document.getElementById('keywords');

    document.getElementById('keywords').addEventListener('input', (e) =>{

        if(keywords.value.length >= 3){

            // On récupère l'url active
            const Url = new URL(window.location.href);

            let url = `${Url}?q=${keywords.value}`;

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
               // history.pushState({}, null, url.pathname + "?" + params.toString());
            }).catch(e => alert(e));

        }

    });


}

