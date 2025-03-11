<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Rental (REST API)</title>
    <link rel="stylesheet" href="appStyles.css">
    <script src="appConfig.js"></script>
</head>
<body>
    <div class="page">
        <h1>Car Rental System (with rest api)</h1>
        <div class="mainMenu">
            <h2>Select an option: <h2>
            <ul class="options1">
                <li><button type="button" onClick="showList()"> view all available cars </button></li>
                <li><button type="button" onClick="showSearchForm()"> view specific car </button></li>
                <li><button type="button" onClick="showCarCreationForm()"> create car </button></li>
            </ul>
        </div>

        <div class="availableCars">
            <ul class="carList">
                <?php
                     echo "<li><h2>Car List</h2></li>";
                    $car_arr = serverGetConn();         //server connect and get data into json array

                    //Display GET request (list of cars available)
                    foreach($car_arr as $car){   
                        $c_brand= htmlspecialchars($car['brand']);
                        $c_model= htmlspecialchars($car['model']);
                        $c_id= htmlspecialchars($car['id']);
                        $c_color= htmlspecialchars($car['color']);

                        echo "<li>   Brand: " .$c_brand."  | Model: " .$c_model. "  | Id: ".$c_id. "  | Color: " .$c_color. "</li>";
                    }
                ?>
            </ul>
        </div>
    
        <form class="searchCarForm" action="http://localhost/REST_API/app.php" method="GET">
            Insert brand: <input type="text" name="brand" placeholder="Brand..."><br>
            Insert model: <input type="text" name="model" placeholder="Model..."><br>
            <input type="submit" value="Submit">
        </form>

        <!-- Form result by searching element -->
        <?php
            echo "<div class='carSearchPage'>";
            $car_arr = serverGetConn();
            $car_found=false;

            if(isset($_GET['brand']) && isset($_GET['model'])){
                $car_brand = strtolower(trim($_GET['brand']));
                $car_model = strtolower(trim($_GET['model']));

                foreach($car_arr as $car ){
                    $c_brand= strtolower(htmlspecialchars($car['brand']));
                    $c_model= strtolower(htmlspecialchars($car['model']));
                    $c_id= htmlspecialchars($car['id']);
                    $c_color= strtolower( htmlspecialchars($car['color']));
                    if($c_brand == $car_brand && $c_model == $car_model){
                        echo "<p><strong>Brand:</strong>".$car_brand;
                        echo "<p><strong>Model:</strong>".$car_model;
                        echo "<p><strong>ID:</strong>".$c_id;
                        echo "<p><strong>Color:</strong>".$c_color;
                        $car_found = true;
                    } 
                }
                if($car_found){
                    echo "<p>Car found!:</p>";
                }
                else{
                    echo "<p>Car not found :(</p>";
                }
            }
            echo "</div>";
        ?>

        <form class="carCreationForm" action="http://localhost/REST_API/app.php" method="POST">
            <h2>Add a new car to collection</h2>
            Insert a brand:<input type="text" name="insBrand" placeholder="Brand"><br>
            Insert a model:<input type="text" name="insModel" placeholder="Model"><br> 
            Insert an ID:<input type="number" name="insID" placeholder="ID"><br>
            Insert a color:<input type="text" name="insColor" placeholder="Color"><br>
            <input type="submit" value="submit">
        </form>

        <?php
        if( $_SERVER['REQUEST_METHOD'] == 'POST' ){
            $carData = [
                "brand" => $_POST['insBrand'],
                "model" => $_POST['insModel'],
                "id" => $_POST['insID'],
                "color" => $_POST['insColor']
            ];

            $json_data = json_encode($carData);
            $api_url = "http://localhost:8080/carRental";

            //for post requests i'll use curl to send data to server
            
            $session = curl_init($api_url);
            curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($session, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($session, CURLOPT_POSTFIELDS, $json_data);
            curl_setopt($session, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($json_data)
            ]);

            $response = curl_exec($session);
            curl_close($session);

            echo "<p>Request sended successfully! :) </p>";
        }

        ?>
        
    </div>

    <!-- Server handling functions -->

    <?php
        function serverGetConn(){
            //GET connect with server
            $api_url="http://localhost:8080/carRental";
            $json_data = file_get_contents($api_url);
            $car_array = json_decode($json_data,true);

            return $car_array;
        }
    ?>

</body>
</html>