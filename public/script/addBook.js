window.onload = () =>{

    let addLink = document.getElementById('addBook');

    addLink.addEventListener('click', (e) => {
        e.preventDefault();

        let href = addLink.getAttribute('href');

        // On lance la requête ajax
        fetch(href, {
            method: "GET",
            headers: {
                "X-Requested-With": "XMLHttpRequest"
            }
        }).then(response => 
            response.json()
        ).then(data => {
            
            // On remplace le contenu
            if(data.status == 200){
                console.log(data.status);
            }
            console.log(data.status);


        }).catch(e => alert(e));
    })



}