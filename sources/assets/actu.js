window.onload = () =>{
    (async () => {

        let data = await fetchActu();
        const element = document.querySelector("#content");
        element.innerHTML = data.content;
    })()
}

const fetchActu = async () => {
    const res = await fetch('/actu', {
        method: "GET",
        headers: {
            "Content-Type": "application/json"
        }
    })
    if (res.ok) {
        return await res.json();
    }
    return {content: ''};
}