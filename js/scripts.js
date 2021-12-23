let artist;
let album;
let noItemsInCart = 0;

const artistHead = `<tr><th>Artist Name</th></tr>`;
const artistLine = (artist) => `<td><a href="#" onclick="goToAlbums(${artist.ArtistId})">${artist.Name}</a></td>`;
const albumHead = `<tr><th>Album Title</th></tr>`;
const albumLine = (album) => `<td><a href="#" onclick="goToTracks(${album.AlbumId})">${album.Title}</a></td>`;
const trackHead = `<tr><th>Track Name</th></tr>`;
const trackLine = (track) => `<td><a href="#" onclick="addToCart(${track.TrackId})">${track.Name}</a></td>`;

const PAGE_SIZE=20;
let page = 0;

let timeout;

function doSearch(event) {
    if (timeout !== undefined) {
        clearTimeout(timeout);
    }
    const text = event.target.value;
    if (text.length > 0) {
        timeout = setTimeout(() => {
            fetchArtists({ query: text }).then(data => populateList('artist', data));
        }, 500);
    } else {
        page = 0;
        fetchArtists({ page }).then(data => populateList('artist', data));
    }
}

function buildBreadCrumbs() {
    const container = document.getElementById('breadcrumbs');
    container.innerHTML = '';

    if (album || artist) {    
        const elem = document.createElement('a');
        elem.setAttribute('href', '#');
        if (artist && album) {
            elem.innerHTML = '< Albums';
            elem.addEventListener('click', () => goToAlbums(artist));
        } else if (artist) {
            elem.innerHTML = '< Artists';
            elem.addEventListener('click', () => goToArtists());
        }

        container.appendChild(elem);
    }
}

function goToArtists() {
    artist = null;
    album = null;
    fetchArtists().then(data => populateList('artist', data));
}

function goToAlbums(artistId) {
    artist = artistId;
    album = null;
    fetchAlbums(artistId).then(data => populateList('album', data));
}

function goToTracks(albumId) {
    album = albumId;
    fetchTracks(albumId).then(data => populateList('track', data));
}

function fetchArtists(args) {
    if (!args || (!args.query && !args.page)) {
        return get(`/artists?limit=${PAGE_SIZE}`);
    } else if (query) {
        return get(`/artists?limit=${PAGE_SIZE}&query=${query}`);
    } else {
        return get(`/artists?limit=${PAGE_SIZE}&offset=${page * PAGE_SIZE}`);
    }
}

function fetchAlbums(artistId) {
    return get(`/artists/${artistId}/albums`);
}

function fetchTracks(albumId) {
    return get(`/albums/${albumId}/tracks`);
}

function populateList(type, data) {
    buildBreadCrumbs();
    if (type === 'artist') {
        document.getElementById('search').style.display = 'block';
    } else {
        document.getElementById('search').style.display = 'none';
    }
    const head = type === 'artist' ? artistHead : (type === 'album' ? albumHead : trackHead);
    document.getElementById('tableHead').innerHTML = head;
    const container = document.getElementById('itemList');
    container.innerHTML = '';
    const line = type === 'artist' ? artistLine : (type === 'album' ? albumLine : trackLine);
    data.forEach(datum => {
        const elem = document.createElement('tr');
        elem.innerHTML = line(datum);
        container.appendChild(elem);
    });
}

function addToCart(trackId) {
    post('/cart', { trackId }).then(res => updateCartButton(res.noTracksInCart));
}

function updateCartButton(numItems) {
    let text = 'Go to cart';
    noItemsInCart = numItems;
    if (numItems > 0) {
        text += ` (${numItems})`;
    }
    document.getElementById('btn-cart').textContent = text;
}

function logout() {
    get('/logout').then(() => document.location.href = '/');
}

function showModal() {
    document.getElementById('modalContainer').style.display = 'flex';
    document.querySelector('body').style.overflow = 'hidden';
}

function hideModal() {
    editing = null;
    document.getElementById('modalContainer').style.display = 'none';
    document.querySelector('body').style.overflow = 'auto';
} 

function showCart() {
    get('/cart').then(cartData => {
        updateCartButton(cartData.length);
        document.getElementById('modalTitle').innerHTML = 'Cart';
        populateCart(cartData);
        showModal();
    });
}

function clearCart() {
    del('/cart').then(() => {
        updateCartButton(0);
        hideModal();
    });
}

function populateCart(data) {
    const container = document.getElementById('cartContents');
    container.innerHTML = '';

    const list = document.createElement('ul');
    data.forEach(track => {
        const item = document.createElement('li');
        item.innerHTML = `${track.Album.Artist.Name} - ${track.Name} (${track.Album.Title}) - ${track.UnitPrice}$`;
        item.addEventListener('click', () => deleteFromCart(track.TrackId));
        list.appendChild(item);
    });

    container.appendChild(list);

    const total = document.createElement('p');
    total.innerHTML = `Total: ${data.reduce((total, item) => total += item.UnitPrice, 0).toFixed(2)}$`;

    document.getElementById('modalContent').appendChild(total);
}

function populateCheckout() {
    return get('/customer').then(data => {
        document.getElementById('chk-address').value = data['Address'];
        document.getElementById('chk-city').value = data['City'];
        document.getElementById('chk-state').value = data['State'];
        document.getElementById('chk-country').value = data['Country'];
        document.getElementById('chk-postal').value = data['PostalCode'];
    });
}

function deleteFromCart(trackId) {
    if (confirm('Are you sure you wish to delete this track from your cart?')) {
        del(`/cart/${trackId}`).then(() => get('/cart').then(res => {
            populateCart(res);
            updateCartButton(res.length);
        }));
    }
}

function doCheckout() {
    if (noItemsInCart > 0) {
        const cartVis = document.getElementById('cartContents').style.display;
        if (cartVis !== 'none') {
            populateCheckout().then(() => toggleBilling());
        } else {
            submitCheckout();
        }
    }
}

function submitCheckout() {
    try {
        const billing = {
            Address: document.getElementById('chk-address').value,
            City: document.getElementById('chk-city').value,
            State: document.getElementById('chk-state').value,
            Country: document.getElementById('chk-country').value,
            PostalCode: document.getElementById('chk-postal').value
        }
        post('/checkout', billing)
            .then(() => {
                alert('Your order has been successfully placed.')
                noItemsInCart = 0;
                hideModal();
            })
            .catch(err => {
                console.error(err);
                alert('There was a problem while placing your order.');
            });
    } catch {
        alert("You must fill in all the billing details");
    }
}

function toggleBilling() {
    const cartVis = document.getElementById('cartContents').style.display;
    if (cartVis === 'none') {
        document.getElementById('modalTitle').innerHTML = 'Cart';
        document.getElementById('cartContents').style.display = 'flex';
        document.getElementById('checkout').style.display = 'none';
    } else {
        document.getElementById('modalTitle').innerHTML = 'Billing';
        document.getElementById('cartContents').style.display = 'none';
        document.getElementById('checkout').style.display = 'flex';
    }
}

window.addEventListener('DOMContentLoaded', () => {
    document.getElementById('search').addEventListener('input', doSearch);
    document.getElementById('btn-logout').addEventListener('click', logout);
    document.getElementById('btn-cart').addEventListener('click', showCart);
    document.getElementById('btn-clear').addEventListener('click', clearCart);
    document.getElementById('btn-submit').addEventListener('click', doCheckout);
    document.getElementById('modalContainer').addEventListener('click', hideModal);
    document.getElementById('modal').addEventListener('click', (e) => {e.stopImmediatePropagation()});
    document.getElementById('modalClose').addEventListener('click', hideModal);
    fetchArtists().then(data => populateList('artist', data));
    get('/cart').then(data => updateCartButton(data.length));
});