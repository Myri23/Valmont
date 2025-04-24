const button = document.getElementById('btn-search');
const searchBlock = document.getElementById('search-panel');

function toggleSearchPanel() {
    searchBlock.classList.toggle('hidden');
}

button.addEventListener('click', toggleSearchPanel);    

window.addEventListener('scroll', function() {
    if(window.scrollY > 0){
        searchBlock.classList.add('hidden');
    }
});
