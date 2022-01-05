const debug = true;

function get(endpoint) {
    return fetch(`${apiRoot}${endpoint}`, {
        method: 'GET',
        credentials: 'include',
    }).then(async res => {
        if (debug) {
            const body = await res.text();
            console.log(`GET ${endpoint}:`, { res, body });
            return JSON.parse(body);
        } else {
            return res.json();
        }
    });
}

function post(endpoint, data) {
    return fetch(`${apiRoot}${endpoint}`, {
        method: 'POST',
        headers: {"Content-type": "application/json; charset=UTF-8"},
        body: JSON.stringify(data),
        credentials: 'include',
    }).then(async res => {
        if (debug) {
            const body = await res.text();
            console.log(`POST ${endpoint}:`, { res, body });
            return JSON.parse(body);
        } else {
            return res.json();
        }
    });
}

function put(endpoint, data) {
    return fetch(`${apiRoot}${endpoint}`, {
        method: 'PUT',
        headers: {"Content-type": "application/json; charset=UTF-8"},
        body: JSON.stringify(data),
        credentials: 'include',
    }).then(async res => {
        if (debug) {
            const body = await res.text();
            console.log(`PUT ${endpoint}:`, { res, body });
            return JSON.parse(body);
        } else {
            return res.json();
        }
    });
}

function del(endpoint) {
    return fetch(`${apiRoot}${endpoint}`, {
        method: 'DELETE',
        credentials: 'include',
    }).then(async res => {
        if (debug) {
            const body = await res.text();
            console.log(`DELETE ${endpoint}:`, { res, body });
            return JSON.parse(body);
        } else {
            return res.json();
        }
    });
}