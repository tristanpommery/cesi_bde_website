//Imports
var express = require('express');
var bodyParser = require('body-parser');
var apiRouter = require('./apiRouter').router;

// Instantiate server
var server = express();

//Body Parser config
server.use(bodyParser.urlencoded({ extended: true }));
server.use(bodyParser.json());

//Configure CORS
server.use(function(request, response, next) {
    response.header("Access-Control-Allow-Origin", "*");
    response.header("Access-Control-Allow-Headers", "Origin, X-Requested-With, Content-Type, Accept");
    response.header("Access-Control-Allow-Methods", "*");
    next();
});

//Configure routes
server.get('/', function(req,res) {
    res.setHeader('Content-Type', 'text/html');
    res.status(200).send('<h1>Bonjour sur mon super server</h1>');
});

server.use('/api/', apiRouter);

//Launch server
server.listen(8080, function() {
    console.log("Serveur en écoute !")
})