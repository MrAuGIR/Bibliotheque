
const loadPopularityBiblio = async () => {
    let data = await fetchBiblio();

    const element = document.querySelector("#biblioContent");
    element.innerHTML = data;
}

const fetchBiblio = async () => {
    const res = await fetch('/biblio/popularity', {method: 'GET'});

    if (res.ok) {
        return await res.text();
    }
    return "";
}

document.addEventListener('DOMContentLoaded', async function() {
    await loadPopularityBiblio();
})