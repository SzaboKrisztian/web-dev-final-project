<h1>Browse</h1>

<div id="modalContainer">
    <div id="modal">
        <div id="modalHeader">
            <p id="modalTitle"></p>
            <p id="modalClose">×</p>
        </div>
        <div id="modalContent">
            <div id="cartContents"></div>
            <div id="checkout">
                <label for="chk-address">Address</label>
                <input id="chk-address">
                <label for="chk-city">City</label>
                <input id="chk-city">
                <label for="chk-state">State</label>
                <input id="chk-state">
                <label for="chk-country">Country</label>
                <input id="chk-country">
                <label for="chk-postal">Postal Code</label>
                <input id="chk-postal">
            </div>
        </div>
        <div id="modalButtons">
            <button id="btn-clear">Clear</button>
            <button id="btn-submit">Next</button>
        </div>
    </div>
</div>

<div id="breadcrumbs"></div>

<input placeholder="Filter" id="search">
<div id="listContainer">
    <table id="table">
        <colgroup id="colgroups">
            
        </colgroup>
        <thead id="tableHead"></thead>
        <tbody id="itemList"></tbody>
    </table>
</div>

<p id="pagination"></p>

<button id="btn-cart">Go to cart</button>

<button id="btn-editacc">Edit account</button>

<button id="btn-logout">Log out</button>

<script src="/js/scripts.js"></script>