let request = new XMLHttpRequest();
request.open('GET', 'http://localhost:8080/api/ajax')
request.onload = function(){
    let users = JSON.parse(request.responseText)
    console.log(users[0])
}
request.send()

console.log('HAHAHAHAHAHAH')