const entity = 'Track';
const endpoint = '/tracks';
const pkName = 'TrackId';

const tableHeadings = ['Id', 'Name', 'Album', 'Type', 'Genre', 'Composer', 'Length', 'Size', 'Price'];
// [id, type, label, required]
const inputs = {
    Title: ['input-name', 'text', 'Track name', 'req'],
    AlbumId: ['input-album-id', 'text', 'Album'],
    MediaTypeId: ['input-media-id', 'text', 'Type', 'req'],
    GenreId: ['input-genre-id', 'text', 'Genre'],
    Composer: ['input-composer', 'text', 'Composer'],
    Milliseconds: ['input-length', 'text', 'Length (ms)'],
    Bytes: ['input-size', 'text', 'Size (bytes)'],
    UnitPrice: ['input-price', 'text', 'Price'],
};

function setModalTitleCreate() {
    document.getElementById('modalTitle').innerHTML = 'Add new track';
}

function setModalTitleEdit(id) {
    document.getElementById('modalTitle').innerHTML = `Edit track ID ${id}`;
}

function searchFunc(text) {
    const query = `/tracks?limit=${PAGE_SIZE}${text && text.length > 0 ? `&query=${encodeURIComponent(text)}` : ''}`;
    return get(query);
}

function goToPage(num) {
    get(`/tracks?limit=${PAGE_SIZE}&offset=${num * PAGE_SIZE}`)
        .then(data => {
            page = num;
            populateList(data);
        });
}

function createRow(item, index) {
    const result = document.createElement('tr');
    result.innerHTML = `<td>${item.TrackId}</td><td>${item.Name}</td><td>${item.Album.Title}</td><td>${item.MediaType.Name}</td><td>${item.Genre.Name}</td><td>${item.Composer}</td><td>${item.Milliseconds}</td><td>${item.Bytes}</td><td>${item.UnitPrice}</td><td><p onclick="doEdit(${index})">✎</p></td>`;
    return result;
}