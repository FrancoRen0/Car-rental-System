
//made with express.js, a web application framework...

const express = require("express");
const app = express();
const PORT = 8080;

//Execute the middleware for json parsing..
//Every request will go to the 'express' middleware so they will understand json...
app.use(express.json());

app.listen(PORT,
    ()=> console.log('server is ready on http://localhost:${PORT}')
);

//Cars in database
const cars =[{brand: 'Volkswagen',model: 'Amarok', id: 23319, color: 'black'},
            {brand: 'Nissan',model: 'Frontier', id: 11902, color: 'Metal-blue'},
            {brand: 'Ford',model: 'F-150', id: 23421, color: 'grey'},
            {brand: 'Alfa-Romeo',model: 'Giulietta', id: 76212, color: 'red'}
            ];

//Get all cars
app.get('/carRental', (req,res)=>{
    res.status(200).send(cars);
});

app.get('/carRental/:id',(req,res)=>{
    const carId = parseInt(req.params.id);
    const car = cars.find((car)=>{
        return car.id == carId});

    if(!car){
        res.status(418).send(console.log("Car not found in database"));
    }
    else{
        res.status(200).send(car);
    }
});


app.post('/carRental',(req,res)=>{
    const {id} = req.body;
    const {brand} = req.body;
    const {model} = req.body;
    const {color} = req.body;

    if(!id || !brand || !model || !color){
        res.status(400).send({message:'Please, provide car details: '});
    }
    const existingCar = cars.find((car)=> {return car.id == id});
    if(existingCar){
        res.status(418).send({message:'Car ID already exists!'});
    }

    const newCar = {brand,model,id,color};
    cars.push(newCar);
    console.log("New car added successfuly!:)");
    res.status(200).send({car: newCar});

});



