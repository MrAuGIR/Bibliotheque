
const submitTagSearch = (tagCode, id) => {
    document.getElementById('selectedTag' + id).value = tagCode;
    document.getElementById('tagSearchForm' + id).submit();
}

document.addEventListener('DOMContentLoaded', function() {
    console.log("DOMContentLoaded querySelectorAll js-biblio-tag-search");

    setTimeout(() => {
        document.querySelectorAll(['js-biblio-tag-search']).forEach(element => {
            console.log(element);
            element.addEventListener('click', function() {

                const id = element.getAttribute('data-biblio');
                const tagCode = element.getAttribute('data-code')
                document.getElementById('selectedTag' + id).value = tagCode;
                document.getElementById('tagSearchForm' + id).submit();
            })
        })
    }, 100);

})

