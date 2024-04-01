
function addRemoveApiBook(){
    let links = document.querySelectorAll("[js-link]");

    for (link of links){

        link.addEventListener('click', function(e){
            e.preventDefault();

            const spanStatus = this.querySelector('span.js-status');
            const divResult = document.getElementById('query-result');
            const p = divResult.querySelector('p');

            const Url = new URL(window.location.href);
            
                // On lance la requête ajax
            fetch(this.getAttribute("href"), {
                method: "GET",
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    "Content-Type": "application/json"
                }
            }).then(response => 
                response.json()
            ).then(data => {
                
                if(data.status == 200){
                    if(Url.pathname == '/biblio'){
                        this.parentElement.parentElement.parentElement.remove();
                    }else{
                        if(this.classList.contains('add-book')){
                            spanStatus.textContent = "Supprimer de la biblio";
                            this.classList.replace('add-book','remove-book');
                        }else{
                            spanStatus.textContent= "ajouter à la biblio";
                            this.classList.replace('remove-book','add-book');
                        }
                    }
                    
                    divResult.classList.add('alert-success');
                    p.textContent = data.message;

                }
                


            }).catch(e => alert(e));

        })


    }
}

function removeBookFromBiblio(){
    //on selection les liens des livres créé par l'utilisateur
    let links = document.querySelectorAll("[user-link]");

    for( link of links){
        link.addEventListener('click', function(e){

            e.preventDefault()
            const divResult = document.getElementById('query-result');
            const p = divResult.querySelector('p');


               // On lance la requête ajax
            fetch(this.getAttribute("href"), {
                method: "GET",
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    "Content-Type": "application/json"
                }
            }).then(response => 
                response.json()
            ).then(data => {
                
                if(data.status == 200){
                    
                    this.parentElement.parentElement.parentElement.remove();
                    
                    divResult.classList.add('alert-success');
                    p.textContent = data.message;
                }
            }).catch(e => alert(e));
        });
    }
}

 



    






