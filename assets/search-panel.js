const searchButton = document.getElementById('btn-search');
const closeButton = document.getElementById('btn-close');
const searchBlock = document.getElementById('search-panel');

function toggleSearchPanel() {
    searchBlock.classList.toggle('hidden');
}

searchButton.addEventListener('click', toggleSearchPanel);    
closeButton.addEventListener('click', toggleSearchPanel);  

window.addEventListener('scroll', function() {
    if(window.scrollY > 0){
        searchBlock.classList.add('hidden');
    }
});

window.addEventListener('click', (event) => {
    if (!document.getElementById('search-panel').contains(event.target) &&
      !document.getElementById('btn-search').contains(event.target) && 
      !document.getElementById('btn-close').contains(event.target)) {
        searchBlock.classList.add('hidden');
    }
});
