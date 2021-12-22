const entity = 'Album';
const endpoint = '/albums';
const pkName = 'AlbumId';

const tableHeadings = ['Id', 'Title', 'Artist'];
// [id, type, label, required]
const inputs = {
    Title: ['input-title', 'text', 'Album title', 'req'],
    ArtistId: ['input-artist-id', 'text', 'Artist', 'req'],
};

function setModalTitleCreate() {
    document.getElementById('modalTitle').innerHTML = 'Add new album';
}

function setModalTitleEdit(id) {
    document.getElementById('modalTitle').innerHTML = `Edit album ID ${id}`;
}

function searchFunc(text) {
    const query = `/albums?limit=${PAGE_SIZE}${text && text.length > 0 ? `&query=${encodeURIComponent(text)}` : ''}`;
    return get(query);
}

function goToPage(num) {
    get(`/albums?limit=${PAGE_SIZE}&offset=${num * PAGE_SIZE}`)
        .then(data => {
            page = num;
            populateList(data);
        });
}

function createRow(item, index) {
    const result = document.createElement('tr');
    result.innerHTML = `<td>${item.AlbumId}</td><td>${item.Title}</td><td>${item.Artist.Name}</td><td><p onclick="doEdit(${index})">✎</p></td>`;
    return result;
}