function get(endpoint) {
    return fetch(`${apiRoot}${endpoint}`, {
        method: 'GET'
    }).then(res => res.json());
}

function post(endpoint, data) {
    return fetch(`${apiRoot}${endpoint}`, {
        method: 'POST',
        headers: {"Content-type": "application/json; charset=UTF-8"},
        body: JSON.stringify(data)
    }).then(res => res.json());
}

function put(endpoint, data) {
    return fetch(`${apiRoot}${endpoint}`, {
        method: 'PUT',
        headers: {"Content-type": "application/json; charset=UTF-8"},
        body: JSON.stringify(data)
    }).then(res => res.json());
}

function del(endpoint) {
    return fetch(`${apiRoot}${endpoint}`, {
        method: 'DELETE'
    }).then(res => res.json());
}