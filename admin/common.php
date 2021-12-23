        <nav>
            <a href="/admin/artists.php">Artists</a>
            <a href="/admin/albums.php">Albums</a>
            <a href="/admin/tracks.php">Tracks</a>
        </nav>

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

        <button id="btn-create">Add new</button>
        
        <div id="editContainer">
            <div id="modal">
                <div id="modalHeader">
                    <p id="modalTitle"></p>
                    <p id="modalClose">×</p>
                </div>
                <div id="modalInputs">

                </div>
                <div id="modalButtons">
                    <button id="btn-reset">Reset</button>
                    <button id="btn-submit">Submit</button>
                    <button id="btn-delete">Delete</button>
                </div>
            </div>
        </div>

        <div id="relationContainer">
            <div id="modalRel">
                <div id="modalRelHeader">
                    <p id="modalRelTitle"></p>
                    <p id="modalRelClose">×</p>
                </div>
                <input placeholder="Filter" id="search">
                <div id="modalRelResults">

                </div>
            </div>
        </div>

        <script src="/js/api.js"></script>
        <script src="/admin/js/common.js"></script>