let tbody = document.getElementById("tbody")
let sortBy = document.getElementById("sortBy")
let orderBy = document.getElementById("orderBy")
var reverse = false;
var sortFunction = compareByLastName

getUsers()

orderBy.addEventListener('change', (event => {
    if(event.target.value=="desc"){
        reverse=true;
    } else {
        reverse=false;
    }
    getUsers()
}))

sortBy.addEventListener('change', (event => {
    switch(event.target.value){
        case "lastName":
            sortFunction = compareByLastName
            break;
        case "firstName":
            sortFunction = compareByFirstName
            break;
        case "campus":
            sortFunction = compareByCampus
            break;
        case "gender":
            sortFunction = compareByGender
            break;
        case "association":
            sortFunction = compareByAssociation
            break;
        case "role":
            sortFunction = compareByRole
            break;
        default:
            break;
    }
    getUsers()
}))

function compareByLastName(a, b){
    var x = a.last_name.toLowerCase();
    var y = b.last_name.toLowerCase();
    if (x < y) {return -1;}
    if (x > y) {return 1;}
    return 0;
}

function compareByFirstName(a, b){
    var x = a.first_name.toLowerCase();
    var y = b.first_name.toLowerCase();
    if (x < y) {return -1;}
    if (x > y) {return 1;}
    return 0;
}

function compareByCampus(a, b){
    var x = a.campus.name.toLowerCase();
    var y = b.campus.name.toLowerCase();
    if (x < y) {return -1;}
    if (x > y) {return 1;}
    return 0;
}

function compareByGender(a, b){
    var x = a.genre.toLowerCase();
    var y = b.genre.toLowerCase();
    if (x < y) {return -1;}
    if (x > y) {return 1;}
    return 0;
}

function compareByAssociation(a, b){
    var x = a.association.name.toLowerCase();
    var y = b.association.name.toLowerCase();
    if (x < y) {return -1;}
    if (x > y) {return 1;}
    return 0;
}

function compareByRole(a, b){
    var x = a.roles[0].toLowerCase();
    var y = b.roles[0].toLowerCase();
    if (x < y) {return -1;}
    if (x > y) {return 1;}
    return 0;
}

async function getUsers(){
    let request = new XMLHttpRequest()
    request.open('GET', 'http://localhost:8080/api/users')
    request.onload = function(){
        let users = JSON.parse(request.responseText)
    
        users.forEach(user => {
            if(user.roles[0]==null){
                user.roles[0]="ROLE_USER"
            }
        })
    
        users.sort(sortFunction)
        if(reverse){users.reverse()}

        let table = ''
        users.forEach(user => {
            let row = `<tr>
                <td>${user.first_name}</td>
                <td>${user.last_name}</td>
                <td>${user.email}</td>
                <td>${user.campus.name}</td>
                <td>${user.genre}</td>
                <td>${user.association.name}</td>
                <td>${user.roles[0]}</td>`

            if(user.roles[0]!="ROLE_BDE"){
                row += `<td><button onclick="deleteUser(${user.id})">BAN</button></td>`
            }
            row += `</tr>`
            table += row
            tbody.innerHTML=table
        })
    }
    request.send()
}

async function updateUser(user, newRoles, newAssociationId){
    let request = new XMLHttpRequest()
    request.open('PUT', `http://localhost:8080/api/users/${user.id}`)
    request.setRequestHeader('Content-Type', 'application/json')
    request.onload = function(){

    }
    request.send(JSON.stringify({
        roles: newRoles[0],
        id_association: newAssociationId
    }))
}

async function deleteUser(userId){
    let request = new XMLHttpRequest()
    request.open('DELETE', `http://localhost:8080/api/users/${userId}`)
    request.onload = function(){
        
    }
    request.send()
    getUsers()
}