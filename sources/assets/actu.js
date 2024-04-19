window.onload = () =>{
    (async () => {

        let data = await fetchActu();
        const element = document.querySelector("#content");
        element.innerHTML = data.content;
    })()
}

const fetchActu = async () => {
    const res = await fetch('/actu', {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: {
            "query": "literary"
        }
    })
    if (res.ok) {
        return await res.json();
    }
    return {content: ''};
}