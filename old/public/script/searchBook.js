window.onload = () =>{

    let keywords = document.getElementById('keywords');
    const FilterForm = document.querySelector('#filters');
    let timeout = null;

    document.getElementById('keywords').addEventListener('input', (e) =>{

        e.preventDefault();
        clearTimeout(timeout);
    
        timeout = setTimeout(function(){
            if(keywords.value.length >= 3){

                const Form = new FormData(FilterForm);

                // On fabrique la "queryString"
                const Params = new URLSearchParams();

                //pour chacun des champs 'actif' du formulaire je rajoute un parametre dans l'url
                Form.forEach((value,key) => {
                    Params.append(key, value);
                });
                
                // On récupère l'url active
                const Url = new URL(window.location.href);

                ajaxRequest(Url,Params);

            }
        },1000);
        

    });

    document.getElementById('filterField').addEventListener('change', (e) => {
        
        const Form = new FormData(FilterForm);
        // On fabrique la "queryString"
        const Params = new URLSearchParams();

        //pour chacun des champs 'actif' du formulaire je rajoute un parametre dans l'url
        Form.forEach((value,key) => {
            Params.append(key, value);
        });
        
        // On récupère l'url active
        const Url = new URL(window.location.href);

        //on n'envoie une requète que si le champs texte contient une valeur
        if(keywords.value.length >= 3){
            ajaxRequest(Url,Params);
        }

    });

    document.getElementById('free').addEventListener('change', (e) => {

        const Form = new FormData(FilterForm);

        // On fabrique la "queryString"
        const Params = new URLSearchParams();

        //pour chacun des champs 'actif' du formulaire je rajoute un parametre dans l'url
        Form.forEach((value,key) => {
            Params.append(key, value);
        });
        
        // On récupère l'url active
        const Url = new URL(window.location.href);

        //on n'envoie une requète que si le champs texte contient une valeur
        if(keywords.value.length >= 3){
            ajaxRequest(Url,Params);
        }

    });


}

function ajaxRequest(url, params){

    // On lance la requête ajax
    fetch(url.pathname + "?" + params.toString() + "&ajax=1", {
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
        //a chaque changement du contenu on recrée les listeners
        addRemoveApiBook();

        // On met à jour l'url
        history.pushState({}, null, url.pathname + "?" + params.toString());
        console.log(params.toString());
    }).catch(e => alert(e));


}
