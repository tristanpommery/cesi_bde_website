let shop = document.getElementById("shop")
let sortBy = document.getElementById("sortBy")
let orderBy = document.getElementById("orderBy")
let categoryFilter = document.getElementById("category")
let min = document.getElementById("min")
let max = document.getElementById("max")
var reverse = false;
var sortFunction = compareByName
var category = "";
var minPrice;
var maxPrice;

getProducts()

orderBy.addEventListener('change', (event => {
    if(event.target.value=="desc"){
        reverse=true;
    } else {
        reverse=false;
    }
    getProducts()
}))

sortBy.addEventListener('change', (event => {
    switch(event.target.value){
        case "name":
            sortFunction = compareByName
            break;
        case "price":
            sortFunction = compareByPrice
            break;
        case "category":
            sortFunction = compareByCategory
            break;
        default:
            break;
    }
    getProducts()
}))

function compareByPrice(a, b){
    return a.price - b.price;
}

function compareByName(a, b){
    var x = a.name.toLowerCase();
    var y = b.name.toLowerCase();
    if (x < y) {return -1;}
    if (x > y) {return 1;}
    return 0;
}

function compareByCategory(a, b){
    var x = a.category.name.toLowerCase();
    var y = b.category.name.toLowerCase();
    if (x < y) {return -1;}
    if (x > y) {return 1;}
    return 0;
}

categoryFilter.addEventListener('change', (event => {
    category=event.target.value
    getProducts()
}))

function filterByCategory(x){
    if(category!=""){
        return x.category.name==category;
    } else {
        return 1;
    }
}

min.addEventListener('change', (event => {
    minPrice=event.target.value
    getProducts()
}))

max.addEventListener('change', (event => {
    maxPrice=event.target.value
    getProducts()
}))

function filterByPrice(x){
    if(minPrice && maxPrice){
        return x.price>minPrice && x.price<maxPrice;
    } else if(minPrice){
        return x.price>minPrice;
    } else if(maxPrice){
        return x.price<maxPrice;
    } else {
        return 1;
    }
}

async function getProducts(){
    let request = new XMLHttpRequest()
    request.open('GET', 'http://localhost:8080/api/products')
    request.onload = function(){
        let products = JSON.parse(request.responseText)
    
        products=products.filter(filterByCategory)
        products=products.filter(filterByPrice)
        products.sort(sortFunction)
        if(reverse){products.reverse()}

        let productCards = ''
        products.forEach(product => {
            let card = `
            <div class="shopCard">
                <img class="productImage" src=${product.image } alt="productImage">
                <p class="productName">${product.name}</p>
                <p class="productStock">`

            if(product.stock==0){
                card += `Rupture de stock</p>`
            } else {
                card += `Stock: ${product.stock}</p>`
            }

            card += `<p class="productPrice">Prix: `

            if(product.price==0){
                card += `Gratuit</p>`
            } else {
                card += `${product.price} €</p>`
            }
            card += `
            <p class="productCategory">Catégorie: ${product.category.name}</p>
            <a class="goToProduct productButton" href="/shop/${product.id}">Voir</a>
            </div>`

            productCards += card
        })
        if(document.getElementsByClassName('shopCard')){
            removeElementsByClass('shopCard')
        }
        shop.insertAdjacentHTML('afterbegin', productCards)
    }
    request.send()
}

function removeElementsByClass(className){
    let elements = document.getElementsByClassName(className);
    while(elements.length > 0){
        elements[0].parentNode.removeChild(elements[0]);
    }
}