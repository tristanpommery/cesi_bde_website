const express = require('express');
const app = express();

app.get("/", (req,res)=>{
    console.log("Responding to home route");
    res.status(200).send("<h1>Welcome to Home Page</h1>")
});

app.listen(3000 , function () {
    console.log("Server listening on port 3000")
});
