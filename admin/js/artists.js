const entity = 'Artist';
const endpoint = '/artists';
const pkName = 'ArtistId';

const tableHeadings = ['Id', 'Name'];

// [id, type, label, required]
const inputs = {
    Name: ['input-name', 'text', 'Artist name', 'req']
};

function createRow(item, index) {
    const result = document.createElement('tr');
    result.innerHTML = `<td>${item.ArtistId}</td><td>${item.Name}</td><td><p onclick="doEdit(${index})">✎</p></td>`;
    return result;
}
