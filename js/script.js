var keyword = document.getElementById('keyword');
var container = document.getElementById('container');
var tombolCari = document.getElementById('tombol-cari');

// Fungsi untuk memuat data
function loadData(page = 1) {
    var xhr = new XMLHttpRequest();
    var searchQuery = keyword.value;

    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            container.innerHTML = xhr.responseText;
        }
    };

    xhr.open('GET', 'ajax/roti.php?keyword=' + searchQuery + '&page=' + page, true);
    xhr.send();
}

// Event listener untuk live search
keyword.addEventListener('keyup', function() {
    loadData();
});

// Event listener untuk tombol cari (jaga-jaga jika tidak ada JS)
tombolCari.addEventListener('click', function(event) {
    event.preventDefault();
    loadData();
});

// Event listener untuk pagination link
document.addEventListener('click', function(event) {
    if (event.target.classList.contains('page-link')) {
        event.preventDefault();
        var page = event.target.getAttribute('data-page');
        loadData(page);
    }
});
