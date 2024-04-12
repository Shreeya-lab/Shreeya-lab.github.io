<?php
$servername="localhost";
$dbname="esp32_db";
$username="root";
$password="";	
$conn=mysqli_connect($servername, $username, $password, $dbname);

if(!$conn)
{
	die("Database conn failed: ". mysqli_connect_error());
}
else
{
	echo "DB connection succesfull</br>";
}
$sql = "SELECT id,Neutral,Safe,Moderate,Alert,Danger,Time FROM Monitor order by Time desc limit 40";

$result = $conn->query($sql);

while($data= $result->fetch_assoc())
{
	$sensor_data[]=$data;
}
$readings_time=array_column($sensor_data, 'Time');
$neutral=json_encode(array_reverse(array_column($sensor_data,'Neutral')), JSON_NUMERIC_CHECK);
$safe=json_encode(array_reverse(array_column($sensor_data,'Safe')), JSON_NUMERIC_CHECK);
$moderate=json_encode(array_reverse(array_column($sensor_data,'Moderate')), JSON_NUMERIC_CHECK);
$alert=json_encode(array_reverse(array_column($sensor_data,'Alert')), JSON_NUMERIC_CHECK);
$danger=json_encode(array_reverse(array_column($sensor_data,'Danger')), JSON_NUMERIC_CHECK);
$reading_time = json_encode(array_reverse($readings_time), JSON_NUMERIC_CHECK);
$result->free();
$conn->close();
?>
<!DOCTYPE html>
<html>
<meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://code.highcharts.com/highcharts.js"></script>
	  <style>
		body {
		  min-width: 310px;
			max-width: 1280px;
			height: 500px;
		  margin: 0 auto;
		}
		h2 {
		  font-family: Arial;
		  font-size: 2.5rem;
		  text-align: center;
		}
		.container
		{
			float:right;
		}
		
	  </style>
	  <body>
		<h2>FLOOD MONITORING AND ALERTING</h2>
		
		<div id="chart-dangerwaterlevel" class="container" style="position: relative; height:60vh; width:80vw;float=right;"></div>
		<div id="chart-alertwaterlevel" class="container" style="position: relative; height:50vh; width:40vw;float=right;"></div>
        <div id="chart-moderatewaterlevel" class="container" style="position: relative; height:50vh; width:40vw;float=left;"></div>
		<div id="chart-safewaterlevel" class="container" style="position: relative; height:50vh; width:40vw;float=right;"></div>
		<div id="chart-neutralwaterlevel" class="container" style="position: relative; height:50vh; width:40vw;float=right;"></div>
		<script>
		var neutral=<?php echo $neutral; ?>;
		var safe = <?php echo $safe; ?>;
		var moderate = <?php echo $moderate; ?>;
		var alert = <?php echo $alert; ?>;
		var danger = <?php echo $danger; ?>;
		var reading_time = <?php echo $reading_time; ?>;
		
		var chartW=new Highcharts.Chart
		({
		  
		  chart:{ renderTo : 'chart-dangerwaterlevel' },
		  title: { text:'Danger(Level5 Ultrasonic Sensor)' },
		  title: { text:'Danger(Level5 Ultrasonic Sensor)' },
		  series: [{showInLegend: false,data: danger}],
			plotOptions: {
			line: {animation: false,
			  dataLabels: { enabled: true }
			},
			series: { color: '#ff0000' }
		  },
		  xAxis:{ 
		    title:{ text:'Time'},
			type:'datetime',
			categories: reading_time
		  },
		  yAxis: 
		  {
			title: { text:'Waterlevel(feet)'}
		  },
		  credits: { enabled: false }
		});
		
	    var chartA=new Highcharts.Chart
		({
		  chart:{renderTo :'chart-alertwaterlevel'},
		  title: { text:'Alert(Level4 Float Sensor)' },
		  series: [{showInLegend: false,data: alert}],
		  
			plotOptions: {
			line: { animation: false,
			  dataLabels: { enabled: true }
			},
			series: { color: '#FF8C01' }
		  },
		  xAxis:{ 
		   title:{text:'Time'},
			type: 'datetime',
			categories: reading_time
		  },
		  yAxis: {
			title: { text: 'Waterlevel (feet)' }
			
		  },
		  credits: { enabled: false }
		});
		
		var chartM=new Highcharts.Chart
		({
		  chart:{ renderTo : 'chart-moderatewaterlevel' },
		  title: { text: 'Moderate(Level3 Probe Sensor)'},
		  series: [{showInLegend: false,data:moderate}],
			plotOptions: {
			line: { animation: false,
			  dataLabels: { enabled:true }
			},
			series: { color: '#FFFF00'}
			
		  },
		  xAxis: { 
		   title: { text: 'Time' },
			type: 'datetime',
			categories: reading_time
		  },
		  yAxis: {
			title: { text: 'Waterlevel (feet)' }
			
		  },
		  credits: { enabled: false }
		});
		
		var chartS=new Highcharts.Chart
		({
		  chart:{ renderTo : 'chart-safewaterlevel' },
		  title: { text: 'Safe (Level2 Probe Sensor)' },
		  series: [{showInLegend: false,data: safe}],
			plotOptions: {line: {animation: false,dataLabels: {enabled:true }},
			series: {color:'#0000FF'}},
		  xAxis: { 
		   title: { text: 'Time' },
			type:'datetime',
			categories: reading_time
		  },
		  yAxis: {
			title: { text: 'Waterlevel (feet)' }
			
		  },
		  credits: { enabled: false }
		});
		
		var chartS=new Highcharts.Chart
		({
		  chart:{ renderTo : 'chart-neutralwaterlevel' },
		  title: { text: 'Neutral (Level1 Probe Sensor)' },
		  series: [{showInLegend: false,data: neutral}],
			plotOptions: {line: {animation: false,dataLabels: {enabled:true }},
			series: {color:'#32CD32'}},
		  xAxis: { 
		   title: { text: 'Time' },
			type:'datetime',
			categories: reading_time
		  },
		  yAxis: {
			title: { text: 'Waterlevel (feet)' }
			
		  },
		  credits: { enabled: false }
		});
       </script>
	</body>
</html>