const debug = false;

function get(endpoint) {
    return fetch(`${apiRoot}${endpoint}`, {
        method: 'GET',
        credentials: 'include',
    }).then(res => {
        if (debug) console.log(`GET ${endpoint}:`, res);
        return res.json();
    });
}

function post(endpoint, data) {
    return fetch(`${apiRoot}${endpoint}`, {
        method: 'POST',
        headers: {"Content-type": "application/json; charset=UTF-8"},
        body: JSON.stringify(data),
        credentials: 'include',
    }).then(res => {
        if (debug) console.log(`POST ${endpoint}:`, res);
        return res.json();
    });
}

function put(endpoint, data) {
    return fetch(`${apiRoot}${endpoint}`, {
        method: 'PUT',
        headers: {"Content-type": "application/json; charset=UTF-8"},
        body: JSON.stringify(data),
        credentials: 'include',
    }).then(res => {
        if (debug) console.log(`PUT ${endpoint}:`, res);
        return res.json();
    });
}

function del(endpoint) {
    return fetch(`${apiRoot}${endpoint}`, {
        method: 'DELETE',
        credentials: 'include',
    }).then(res => {
        if (debug) console.log(`DELETE ${endpoint}:`, res);
        return res.json();
    });
}