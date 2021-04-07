
let navSearch = () => {

    let input = document.querySelector('.js-search');

    input.addEventListener('input', (e)=>{
        e.preventDefault();

        if(input.value.length > 2){
            
            // On lance la requête ajax
            fetch('/book/search?keywords='+input.value+'&ajax=1', {
                method: "GET",
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    "Content-Type": "application/json"
                }
            }).then(response => 
                response.json()
            ).then(data => {
                console.log(data)
                makeResult(data.books);
                
            }).catch(e => alert(e));



        }
    })
}


let makeResult = (books) =>{
    
    let div = document.getElementById('div-result');
    
    $html = "<ul>"
    for(book of books){
        $html += `<li class="nav-item" ><a class="nav-link" href="/book/show/${book.id}">${book.volumeInfo.title}</a></li>`
    }
    $html += "</ul>"

    div.innerHTML = $html;

    div.classList.add('js-result-display');


}

navSearch()