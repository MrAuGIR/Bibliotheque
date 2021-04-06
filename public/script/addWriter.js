
let addWriter = () =>{

    let select2 = document.querySelector('.select-writers');
    
    select2.addEventListener('change', function(e){

        console.log('je suis dans le listener de select2')
        let label = this.querySelector("[data-select2-tag=true]");
        console.log(label);

        if(label.length > 0 && (this.indexOf(label.value) != -1)){

            fetch("/writer/add/ajax"+label.value, {
                method: "POST",
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    "Content-Type": "application/json"
                }
            }).then(response => 
                response.json()
            ).then(data => {
                
                // On remplace le contenu
                //on rajoute la nouvelle option pour ne pas qu'elle ne soit encore considéré comme nouvelle
                label.innerHTML = `<option selected value="${data.id}">${label.val()}</option>`;
                
            }).catch(e => alert(e));


        }

    })

}

let fetchBiblio = (url,element) =>{


    fetch(url, {
        method: "POST",
        headers: {
            "X-Requested-With": "XMLHttpRequest",
            "Content-Type": "application/json"
        }
    }).then(response => 
        response.json()
    ).then(data => {
        
        // On remplace le contenu
        //on rajoute la nouvelle option pour ne pas qu'elle ne soit encore considéré comme nouvelle
        element.innerHTML = `<option selected value="${data.id}">${element.value}</option>`;
        
    }).catch(e => alert(e));
  
}