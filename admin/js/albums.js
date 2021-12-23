const entity = 'Album';
const endpoint = '/albums';
const pkName = 'AlbumId';

const tableHeadings = ['Id', 'Title', 'Artist'];
// [id, type, label, required]
const inputs = {
    Title: ['input-title', 'text', 'Album title', 'req'],
    ArtistId: ['input-artist-id', 'rel-artist', 'Artist', 'req'],
};

function createRow(item, index) {
    const result = document.createElement('tr');
    result.innerHTML = `<td>${item.AlbumId}</td><td>${item.Title}</td><td>${item.Artist.Name}</td><td><p onclick="doEdit(${index})">✎</p></td>`;
    return result;
}
