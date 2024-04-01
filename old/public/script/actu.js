window.onload = () =>{


    // On lance la requête ajax
    fetch('/actu', {
        method: "GET",
        headers: {
            "X-Requested-With": "XMLHttpRequest",
            "Content-Type": "application/json"
        }
    }).then(response => 
        response.json()
    ).then(data => {
        // On va chercher la zone de contenu
        const content = document.querySelector("#content");

        // On remplace le contenu
        content.innerHTML = data.content;
        
    }).catch(e => alert(e));

}