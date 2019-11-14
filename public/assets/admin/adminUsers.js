let request = new XMLHttpRequest();
request.open('GET', 'http://localhost:8080/api/ajax')
request.onload = function(){
    let users = JSON.parse(request.responseText)
    console.log(users)
}
request.send()

console.log('HAHAHAHAHAHAH')