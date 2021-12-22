const noResults = `<tr><td colspan="42">Search returned no results</td></tr>`;

const PAGE_SIZE = 20;

let page = 0;
let timeout;
let items;
let editing;

function populateList(data) {
    const itemList = document.getElementById('itemList');
    items = data;
    if (Array.isArray(data) && data.length > 0) {
        const nodes = data.map(createRow);
        itemList.innerHTML = '';
        nodes.forEach(node => itemList.appendChild(node));
    } else {
        itemList.innerHTML = noResults;
    }
    buildNav();
}

function buildNav() {
    if (page !== null) {
        let prev = null;
        let next = null;
        const nav = document.getElementById('navigation');
        nav.innerHTML = '';
        if (page > 0) {
            prev = document.createElement('a');
            prev.innerHTML = '&lt; Prev';
            prev.setAttribute('href', '#');
            prev.addEventListener('click', () => goToPage(page - 1));
        }
        if (page !== null && items.length === PAGE_SIZE) {
            next = document.createElement('a');
            next.innerHTML = 'Next &gt;';
            next.setAttribute('href', '#');
            next.addEventListener('click', () => goToPage(page + 1));
        }
        const number = document.createElement('span');
        number.innerHTML = `${prev ? ' |' : ''} Page ${page + 1} ${next ? '| ' : ''}`;
        if (prev) {
            if (page > 1) {
                const first = document.createElement('a');
                first.innerHTML = '&lt;&lt; First';
                first.setAttribute('href', '#');
                first.addEventListener('click', () => goToPage(0));
                nav.appendChild(first);
                const separator = document.createElement('span');
                separator.innerHTML = ' | ';
                nav.appendChild(separator);
            }
            nav.appendChild(prev);
        }
        nav.appendChild(number);
        if (next) {
            nav.appendChild(next);
        }
    } else {
        document.getElementById('navigation').innerHTML = '';
    }
}

function hideNav() {
    document.getElementById('navigation').style.display = 'none';
}

function doSearch(event) {
    if (timeout !== undefined) {
        clearTimeout(timeout);
    }
    const text = event.target.value;
    if (text.length > 0) {
        timeout = setTimeout(() => {
            searchFunc(text).then(data => {
                page = null;
                populateList(data);
                hideNav();
            });
        }, 500);
    } else {
        page = 0;
        searchFunc().then(data => populateList(data));
    }
}

function doEdit(index) {
    if (index < items.length) {
        editing = items[index];
        setModalTitleEdit(editing[pkName]);
        document.getElementById('btn-delete').style.visibility = 'visible';
        populateModal();
        showModal();
    }
}

function doCreate() {
    editing = null;
    setModalTitleCreate();
    document.getElementById('btn-delete').style.visibility = 'hidden';
    populateModal();
    showModal();
}

function doSubmit() {
    const data = {};
    let error = false;
    Object.keys(inputs).forEach(key => {
        const elemId = inputs[key][0];
        const req = inputs[key][3] === 'req';
        const value = document.getElementById(elemId).value;
        if (req && !value) {
            alert('Missing required value');
            error = true;
            return;
        }
        if (editing !== null ? value !== editing[key] : true) {
            data[key] = value;
        }
    });
    if (error) return;
    if (editing === null) {
        post(endpoint, data)
            .then(res => {
                alert(`${entity} entry successfully created.`);
                page = null;
                populateList([res]);
                hideModal();
            })
            .catch(err => alert(err));

    } else {
        const id = editing[pkName];    
        put(`${endpoint}/${id}`, data)
            .then(res => {
                console.log(res);
                alert(`${entity} entry successfully updated.`);
                goToPage(page);
                hideModal();
            })
            .catch(err => alert(err));
    }
}

function doDelete() {
    const id = editing[pkName];
    del(`${endpoint}/${id}`)
        .then(() => {
            alert(`${entity} entry successfully deleted.`);
            goToPage(page);
            hideModal();
        })
        .catch(err => alert(err));
}

function populateModal() {
    document.getElementById('modalInputs').innerHTML = '';
    Object.keys(inputs).forEach(key => {
        const [elemId, type, labelTxt, req] = inputs[key];
        const input = document.createElement('input');
        input.setAttribute('id', elemId);
        input.setAttribute('type', type);
        if (req === 'req') {
            input.setAttribute('required', true);
        }
        input.value = editing === null ? '' : editing[key];
        const label = document.createElement('label');
        label.innerHTML = labelTxt;
        document.getElementById('modalInputs').appendChild(label);
        document.getElementById('modalInputs').appendChild(input);
    });
}

function showModal() {
    document.getElementById('editContainer').style.display = 'flex';
    document.querySelector('body').style.overflow = 'hidden';
}

function hideModal() {
    editing = null;
    document.getElementById('editContainer').style.display = 'none';
    document.querySelector('body').style.overflow = 'auto';
}

window.addEventListener('DOMContentLoaded', () => {
    document.getElementById('search').addEventListener('input', doSearch);
    document.getElementById('editContainer').addEventListener('click', hideModal);
    document.getElementById('modal').addEventListener('click', (e) => {e.stopImmediatePropagation()});
    document.getElementById('modalClose').addEventListener('click', hideModal);
    document.getElementById('btn-reset').addEventListener('click', populateModal);
    document.getElementById('btn-submit').addEventListener('click', doSubmit);
    document.getElementById('btn-delete').addEventListener('click', doDelete);
    document.getElementById('btn-create').addEventListener('click', doCreate);
    let html = '<tr>';
    tableHeadings.forEach(heading => html += `<th>${heading}</th>`);
    html += '<th>Edit</th></tr>';
    document.getElementById('tableHead').innerHTML = html;
    html = `<col id="col-id">${'<col>'.repeat(tableHeadings.length - 1)}<col id="col-edit">`;
    document.getElementById('colgroups').innerHTML = html;
    searchFunc().then(data => populateList(data));
});