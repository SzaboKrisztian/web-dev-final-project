const entity = 'Artist';
const endpoint = '/artists';
const pkName = 'ArtistId';

const tableHeadings = ['Id', 'Name'];
// [id, type, label, required]
const inputs = {
    Name: ['input-name', 'text', 'Artist name', 'req']
};

function setModalTitleCreate() {
    document.getElementById('modalTitle').innerHTML = 'Add new artist';
}

function setModalTitleEdit(id) {
    document.getElementById('modalTitle').innerHTML = `Edit artist ID ${id}`;
}

function searchFunc(text) {
    const query = `/artists?limit=${PAGE_SIZE}${text && text.length > 0 ? `&query=${encodeURIComponent(text)}` : ''}`;
    return get(query);
}

function goToPage(num) {
    get(`/artists?limit=${PAGE_SIZE}&offset=${num * PAGE_SIZE}`)
        .then(data => {
            page = num;
            populateList(data);
        });
}

function createRow(item, index) {
    const result = document.createElement('tr');
    result.innerHTML = `<td>${item.ArtistId}</td><td>${item.Name}</td><td><p onclick="doEdit(${index})">✎</p></td>`;
    return result;
}