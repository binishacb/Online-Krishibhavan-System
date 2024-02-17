<?php
if (isset($_POST["submit"])) {
    if (empty($_POST["city"])) {
        echo "Enter the city name";
    } else {
        $city = $_POST["city"];
        $api_key = "0509de76013d54c410cb07bbc693b43f";
        $api_url = "http://api.openweathermap.org/data/2.5/forecast?q=$city&appid=$api_key";

        $api_data = file_get_contents($api_url);
        $weather = json_decode($api_data, true);
    }
}
?>
<html>

<body>
    <form method="POST">
        <h1>Weather app</h1>
        <input type="text" name="city" id="city">
        <input type="submit" name="submit" value="check weather">
    </form>

    <?php
    if (isset($weather)) {
        $forecastData = $weather["list"];
        $previousDate = null;

        foreach ($forecastData as $forecast) {
            $date = date("Y-m-d", $forecast["dt"]);

            // Skip redundant data for the same day
            if ($date !== $previousDate) {
                $iconCode = $forecast["weather"][0]["icon"];
                $iconUrl = "http://openweathermap.org/img/w/$iconCode.png";

                echo "<div class='weather-forecast'>";
                echo "<p>Date: $date</p>";
                echo "<img src='$iconUrl' class='weather-icon'/>";
                echo ucwords($forecast["weather"][0]["description"]);
                echo "<br>";
                echo "Temperature: " . ($forecast["main"]["temp"] - 273) . "&deg;C";
                echo "<br>Humidity: " . $forecast["main"]["humidity"] . "%";
                echo "<br>Wind: " . $forecast["wind"]["speed"] . " km/h";
                echo "</div>";

                $previousDate = $date; // Update previous date
            }
        }
    }
    ?>
</body>

</html>
