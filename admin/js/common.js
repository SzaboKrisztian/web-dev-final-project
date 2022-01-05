const noResults = `<tr><td colspan="42">Search returned no results</td></tr>`;

const PAGE_SIZE = 20;

let page = 0;
let timeout;
let items;
let editing;
let relatedItems;
let relationEditing;
let newRelationTarget;

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
        const nav = document.getElementById('pagination');
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
        document.getElementById('pagination').innerHTML = '';
    }
}

function hideNav() {
    document.getElementById('pagination').style.display = 'none';
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
        editing = JSON.parse(JSON.stringify(items[index]));
        document.getElementById('modalTitle').innerHTML = `Edit ${entity.toLowerCase()} ID ${editing[pkName]}`;
        document.getElementById('btn-delete').style.visibility = 'visible';
        populateModal();
        showModal();
    }
}

function doCreate() {
    editing = null;
    document.getElementById('modalTitle').innerHTML = `Add new ${entity.toLowerCase()}`;
    document.getElementById('btn-delete').style.visibility = 'hidden';
    populateModal();
    showModal();
}

function doSubmit() {
    const data = {};
    const keys = Object.keys(inputs);
    for (let i = 0; i < keys.length; i += 1) {
        const key = keys[i];
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
    }
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

        let input = document.createElement('input');
        input.setAttribute('id', elemId);
        input.setAttribute('type', type.startsWith('rel-') ? 'hidden' : type);
        if (req === 'req') {
            input.setAttribute('required', '');
        }
        if (type.startsWith('rel-')) {
            const target = type.substring(4);
            const key2 = key.substring(0, key.length - 2);
            const name = key2 === 'Album' ? 'Title' : 'Name';
            input.value = editing === null ? '' : editing[key];
            
            document.getElementById('modalInputs').appendChild(input);
            
            input = document.createElement('input');
            input.setAttribute('id', elemId.substring(0, elemId.length - 3));
            input.value = editing[key2][name];
            input.addEventListener('focus', () => editRelation(target, input, key, name));
        } else {
            input.value = editing === null ? '' : editing[key];
        }
        const label = document.createElement('label');
        label.innerHTML = labelTxt;
        document.getElementById('modalInputs').appendChild(label);
        document.getElementById('modalInputs').appendChild(input);
    });
}

function showRelModal() {
    document.getElementById('relationContainer').style.display = 'flex';
}

function hideRelModal() {
    relatedItems = null;
    document.getElementById('relationContainer').style.display = 'none';
}

function editRelation(target, input, key, nameProp) {
    get(`/${target}`).then(data => {
        newRelationTarget = null;
        relatedItems = data;
        relationEditing = [input, key, nameProp];
        populateRels(relatedItems);
        document.getElementById('relFilter').value = '';
        document.getElementById('modalRelTitle').innerHTML = `Pick ${target.substring(0, target.length - 1)}`;
        showRelModal();
    });
}

function doFilterRels(event) {
    const text = event?.target?.value ?? null;

    if (timeout !== undefined) {
        clearTimeout(timeout);
    }

    if (text && text.length > 0) {
        const nameKey = relationEditing[2];
        const items = relatedItems.filter(i => i[nameKey] && i[nameKey].includes(text));
        populateRels(items);
    } else {
        populateRels(relatedItems);
    }
}

function populateRels(items) {
    items = items.length > 10 ? items.slice(0, 10) : items;
    const container = document.getElementById('modalRelResults');
    container.innerHTML = '';
    
    items.forEach(item => {
        const elem = document.createElement('li');
        const pk = relationEditing[1];
        const name = relationEditing[2];
        elem.innerHTML = `${item[pk]}: ${item[name]}`
        elem.className = 'relationItem';
        elem.addEventListener('click', () => {
            const hiddenId = inputs[pk][0];
            const visId = hiddenId.substring(0, hiddenId.length - 3);
            document.getElementById(hiddenId).value = item[pk];
            document.getElementById(visId).value = item[name];
            hideRelModal();
        });
        container.appendChild(elem);
    });
}

function searchFunc(text) {
    const query = `${endpoint}?limit=${PAGE_SIZE}${text && text.length > 0 ? `&query=${encodeURIComponent(text)}` : ''}`;
    return get(query);
}

function goToPage(num) {
    get(`${endpoint}?limit=${PAGE_SIZE}&offset=${num * PAGE_SIZE}`)
        .then(data => {
            page = num;
            populateList(data);
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

function logout() {
    get('/logout').then(() => document.location.reload());
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
    document.getElementById('btn-logout').addEventListener('click', logout);
    let html = '<tr>';
    tableHeadings.forEach(heading => html += `<th>${heading}</th>`);
    html += '<th>Edit</th></tr>';
    document.getElementById('tableHead').innerHTML = html;
    html = `<col id="col-id">${'<col>'.repeat(tableHeadings.length - 1)}<col id="col-edit">`;
    document.getElementById('colgroups').innerHTML = html;
    searchFunc().then(data => populateList(data));
    document.getElementById('relationContainer').addEventListener('click', hideRelModal);
    document.getElementById('modalRel').addEventListener('click', (e) => {e.stopImmediatePropagation()});
    document.getElementById('modalRelClose').addEventListener('click', hideRelModal);
    document.getElementById('relFilter').addEventListener('input', doFilterRels);
});
