relatedBook = () =>{

    let author = document.querySelector('.js-author');
    console.log(author.textContent);
    // On lance la requête ajax
    url = `/book/show/related/${author.textContent}`;
    fetch(url, {
        method: "GET",
        headers: {
            "X-Requested-With": "XMLHttpRequest",
            "Content-Type": "application/json"
        }
    }).then(response => 
        response.json()
    ).then(data => {
        // On va chercher la zone de contenu
        const content = document.querySelector("#related-book");

        // On remplace le contenu
        content.innerHTML = data.related-book;
        
    }).catch(e => alert(e));

}

window.onload = () =>{
    relatedBook();
}