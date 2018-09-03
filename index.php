<?php

include 'race.php';

$selected = '';
$selected2 = '';
$selected3 = '';


function dbConnect()
{
	extract(include('config.php'));
    $connect = mysqli_connect($host, $username, $password, $dbname);

    if (!$connect)
    {
        echo "Nem lehet megnyitni az adatbázist! " . mysqli_connect_error();
        exit();
    }

    $utf = mysqli_query($connect, "SET NAMES utf8");
    if (!$utf) {
        echo "utf8 nem működik! " . mysqli_connect_error();
        exit();
    }
		
    return $connect;
}

function getArray($query,$var1,$var2)
{

    $conn = dbConnect();

    $results = mysqli_query($conn, $query);
    $resultsArray = array();

    if ($results)
    {
        while ($row = mysqli_fetch_assoc($results))
        {
            $resultsArray[$row[$var1]] = $row[$var2];  //előfutam => 0, Középdöntő => 1 stb.
                                                        // férfi hátúszás => 31, férfi mellúszás => 10 stb.
                                                        // 0 => 0 ; 1 =>1; 2=>2 stb.
        }
    }
    mysqli_close($conn);
    print_r($resultsArray);
    return $resultsArray;

}

function get_options($select,$sql,$_var1, $_var2)
{

    $var1 = $_var1;
    $var2 = $_var2;
    $resultsArray = getArray($sql,$var1,$var2);


    $options = '';
    while(list($key,$val)=each($resultsArray))
    {
        if($select == $val)
        {
            $options .= '<option value="'.$val.'" selected>'.$key.'</option>';
        }

        else
        {
            $options .= '<option value="'.$val.'">'.$key.'</option>';
        }
    }

    return $options;
}

function displayInTable($displayed)
{
    $rows = '';

    if ($displayed)
    {

        while ($row = mysqli_fetch_array($displayed))
        {
            $rows .= '</td><td>' . $row['cat'] . '</td><td>' . $row['lane'] . '</td><td>' . $row['name'] . '</td><td>' . $row['birthYear'] . '</td><td>' . $row['club'] . '</td><td>'. $row['coach'] . '</td><td>' . $row['membersOfRelay'] . '</td><td>'. $row['time'] . '</td><td>' . $row['rank'] . '</td></tr>';

        }

    }
    return $rows;
}


//pl. round = 0
if(isset($_POST['round']))
{
    $selected = $_POST['round'];
    //echo  $selected;
}
//category = 3
if(isset($_POST['category']))
{
    $selected2 = $_POST['category'];
    //echo  $selected2;
}
//heat = 5
if(isset($_POST['heat']))
{
    $selected3 = $_POST['heat'];
    //echo  $selected3;
}


?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="keywords" content="úszás, eredmény, ob">
		<title> SwimResults </title>
		<link rel="stylesheet" type="text/css" href="bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>
	<body>
		<header class="container-fluid py-2 text-center">
				<h1>Úszóversenyek élő online közvetítése</h1>
				<h2><?php echo $raceName?></h2>
		</header>

		<div id="banner">
				<div class="mySlides" id="elso"></div>
				<div class="mySlides" id="masodik"></div>
				<div class="mySlides" id="harmadik"></div>
				<div class="mySlides" id="negyedik"></div>
				<div class="mySlides" id="otodik"></div>
				<div class="mySlides" id="hatodik"></div>

				<div class="mySlides" id="nyolcadik"></div>
				<div class="mySlides" id="kilencedik"></div>
				<div class="mySlides" id="tizedik"></div>
				<div class="mySlides" id="tizenegyedik"></div>
				<div class="mySlides" id="tizenkettedik"></div>
				<div class="mySlides" id="tizenharmadik"></div>
				<div class="mySlides" id="tizennegyedik"></div>
				<div class="mySlides" id="tizenotodik"></div>
				<div class="mySlides" id="tizenhatodik"></div>
				<div class="mySlides" id="tizenhetedik"></div>
				<div class="mySlides" id="tizennyolcadik"></div>
				<div class="mySlides" id="tizenkilencedik"></div>
				<div class="mySlides" id="huszadik"></div>
				<div class="mySlides" id="huszonegyedik"></div>
				<div class="mySlides" id="huszonkettedik"></div>
				<div class="mySlides" id="huszonharmadik"></div>

				<div class="mySlides" id="huszonhetedik"></div>
				
			</div>
	<main class="container">
			<script>
			var myIndex = 0;
			carousel();

			function carousel() {
				var i;
				var x = document.getElementsByClassName("mySlides");
				for (i = 0; i < x.length; i++) {
				   x[i].style.display = "none";  
				}
				myIndex++;
				if (myIndex > x.length) {myIndex = 1}    
				x[myIndex-1].style.display = "block";  
				setTimeout(carousel, 5000); // Change image every 2 seconds
			}
			</script>

			<form action="index.php" method="POST">
				<div class="row pb-5 pt-3">
					<div class="col-sm-3">
						<select class="form-control" name="round" onchange="this.form.submit();">
							<option class = "options">KÖR</option>
							<?php
							$sql1 = "SELECT idRound,REPLACE(title,'õ','ő') AS title FROM `lstround`";
							$variable1 = 'title';
							$variable2 = 'idRound';
							echo get_options($selected,$sql1,$variable1,$variable2);
							?>
						</select>
					</div>
					<div class="col-sm-3">
						<select class="form-control" name="category" onchange="this.form.submit();">
							<option class = "options">KATEGÓRIA</option>
							<?php
							$sql2 = "SELECT DISTINCT event,REPLACE(cat,'noi','női') AS cat FROM `results` WHERE cat IS NOT NULL  AND round = '$selected'ORDER BY cat";
							$variable3 = 'cat';
							$variable4 = 'event';
							echo get_options($selected2,$sql2,$variable3,$variable4);
							?>
						</select>
					</div>
					<div class="col-sm-3">
						<select class="form-control" name="heat" onchange="this.form.submit();">
							<option class = "options">FUTAM</option>
							<?php
							$sql3 = "SELECT DISTINCT heat FROM `results` WHERE event = '$selected2'";
							$variable5 = 'heat';
							$variable6 = 'heat';
							echo get_options($selected3,$sql3,$variable5,$variable6);
							?>
						</select>
					</div>
					<div class="col-sm-3">
						<input class="form-control refresh" type="button" value="Frissítés" onClick="location.href=location.href">
					</div>
				</div>			
			</form>
			
			<div class="row">
				 <div class="col-12">
					<table class="table table-striped">
						<thead>
						<tr>
							<td>Kategória</td>
							<td>Pálya</td>
							<td>Név</td>
							<td>Szül.idő</td>
							<td>Klub</td>
							<td>Edző</td>
							<td>Váltótagok</td>
							<td>Idő</td>
							<td>Hely</td>
						</tr>
						</thead>
						<tbody>
						<?php
						if(isset($_POST['round']) && isset($_POST['category']) && isset($_POST['heat']))
						{
							$conn2 = dbConnect();

							$queryDisplayed = "SELECT REPLACE(cat,'noi','női') AS cat,lane,name,birthYear,club,coach,membersOfRelay,time,rank FROM results WHERE round = '$selected' AND event = '$selected2' AND heat = '$selected3'";
							$getDisplayed = mysqli_query($conn2,$queryDisplayed);
							mysqli_close($conn2);
							echo displayInTable($getDisplayed);
						}
						?>

						</tbody>
					</table>
				</div>
			</div>
			<div class="row">
				 <div class="col-12 text-center">

					<label>*DNS = nem rajtolt el</label>
					<br>
					<label> DQS = kizárták</label>
					<br>
					<label> DNF = nem ért célba</label>
						<br>
						<label> n.a. = nincs nevezési adat</label>
						<br>
						<label> "-" = nem a kategóriához tartozó adat/időeredmény hiánya</label>
				</div>
			</div>
		</main>
		<footer class="container-fluid pt-2 pb-1 text-center">
				<p>Copyright &copy; Horváth Zsolt</p>
		</footer>
	</body>
</html>






