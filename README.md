
# Table of Contents

1.  [API](#org6fbc8ae)
    1.  [Public](#orgda776fe)
    2.  [User](#orgb85beed)
    3.  [Admin](#org342d809)

<a id="org6fbc8ae"></a>

# API

<a id="orgda776fe"></a>

## Public

1.  GET
    -   GET /api/logout

2.  POST:
    -   POST /api/login
    -   POST /api/signup

<a id="orgb85beed"></a>

## User

1.  GET:
    -   GET /api/artists
    -   GET /api/artists/$id
    -   GET /api/albums
    -   GET /api/albums/$id
    -   GET /api/tracks
    -   GET /api/tracks/$id
    -   GET /api/genres
    -   GET /api/genres/$id
    -   GET /api/mediatypes
    -   GET /api/mediatypes/$id
    -   GET /api/customer - get account
    -   GET /api/cart - get all items in own cart

2.  POST:
    -   POST /api/cart - add item to cart
    -   POST /api/checkout - purchase items in cart

3.  PUT:
    -   PUT /api/customer - edit account details

4.  DELETE:
    -   DELETE /api/cart - clear cart
    -   DELETE /api/cart/$id - remove item from cart

<a id="org342d809"></a>

## Admin

1.  POST create one:
    -   POST /api/artists
    -   POST /api/albums
    -   POST /api/tracks

2.  PUT update one:
    -   PUT /api/artists/$id
    -   PUT /api/albums/$id
    -   PUT /api/albums/$id

3.  DELETE delete one:
    -   DELETE /api/artists/$id
    -   DELETE /api/albums/$id
    -   DELETE /api/tracks/$id