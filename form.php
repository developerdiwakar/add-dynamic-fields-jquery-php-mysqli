<?php 

$host = 'localhost';
$user = 'root';
$pass = '';
$database = 'diwakar';


$mysqli = new mysqli($host, $user, $pass, $database);

if ($mysqli->connect_error) {
	# code...
	echo "Database Connection error....";
}else{
	echo "Successfully Connected";
}

if (isset($_POST["submit"])) {

	$count = count($_POST['org_name']);

	for ( $i = 0 ; $i < $count ; $i++  ) {

		
		$org_name = $mysqli->real_escape_string($_POST['org_name'][$i]);
		$title = $mysqli->real_escape_string($_POST['title'][$i]);
		$location = $mysqli->real_escape_string($_POST['location'][$i]);
		$start_date = date('Y-m-d H:i:s', strtotime($_POST['start_date'][$i] ) );
		if (isset($_POST['cur_working'][$i]) && !empty($_POST['cur_working'][$i]) ) {
			$cur_working[$i] = $_POST['cur_working'][$i];
			echo "<pre>";
			var_dump($cur_working[$i]);
			die;
			if ($cur_working[$i] === "on") {
				$end_date = 0;
			}
		}else{
			$end_date = date('Y-m-d H:i:s', strtotime($_POST['end_date'][$i] ) );
		}
		$about_me = isset($_POST['about_me'][$i]) ? $_POST['about_me'][$i] : '';

		$query = "INSERT INTO work_experience ( org_name,title,location,start_date,end_date,
												about_me ) " . 
										"VALUES ('$org_name', '$title', '$location', '$start_date', '$end_date', '$about_me')";
		$result = $mysqli->query($query);	
		//var_dump($result);
		if (!$result) {
			print_r($mysqli->error);		
		}else{

			$rows_count = $result->num_rows;
		}


	}	
	
	if (isset($rows_count) > 0) {
		# code...
		echo "Data Inserted Successfully";
	}
	else
		echo "Error! Sorry, Data not inserted.";

	$mysqli->close();
}


?>
<!DOCTYPE html>
<html>
<head>
	<title>Work Experience Form</title>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link href="css/bootstrap-datepicker.css" rel="stylesheet">
	<link rel="stylesheet" href="css/parsley.css">
</head>
<body>
<h2>Work Experience Form</h2>
<div id="div_contanier" class="container">
	<form id="work_exp_form" name="work_exp_form" method="post" autocomplete="on">
	<div id="div_com_fields">
		
	<div class="row">
		<div class="col-md-12">
			<h3>Add Experience</h3>
			<div class="row">
				<div class="col-md-4">
					<input type="text" name="org_name[]" class="form-control" placeholder="Organisation Name.." required>
				</div>
				<div class="col-md-5">
					<input type="text" name="title[]" class="form-control" placeholder="Profile Title.." required>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
				<div class="row">
					<div class="col-md-4">
						<input type="text" name="location[]" class="form-control" placeholder="Location" required>
					</div>
					<div class="col-md-2">
						<input type="text" id="start_date" name="start_date[]" class="form-control" placeholder="Start Date" required>
						
					</div>
					<div class="col-md-2">
						<input type="text" id="end_date" name="end_date[]" class="form-control" placeholder="End Date" required>
					</div>
					<div class="col-md-2">
						<input type="checkbox" id="cur_working" name="cur_working[]">
						<label> Currently Working.</label>
						
					</div>
				</div>		
		</div>

	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="row">
				<div class="col-md-4">
					<textarea id= "about_me" class="form-control" rows="5" name="about_me[]" placeholder="About Me..." required></textarea>
				</div>
				<div class="col-md-3 text-bottom">
					<button id="addMoreRows" type="button" name="add_fields" class="btn btn-success" 
									">Add More</button>
				</div>
			</div> 
		</div>
	</div>
	<div id="addedRows"></div>
	<div class="row">
		<div class="col-md-1"></div>
	</div>
	<div class="row">
		<div class="col-md-10">
			<div class="row">
				<div class="col-md-6">
					<button type="submit" name="submit" class="btn btn-primary">Submit</button>
				</div>
			</div>
		</div>
	</div>
	
	</div>
</form>
</div>
<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="js/bootstrap-datepicker.js"></script>
<script src="js/parsley.min.js"></script>

<script type="text/javascript"> 

var rowCount = 1; 
$(document).ready(function(){

	/* Parsley Call*/
	$("#work_exp_form").parsley();


	/*Hiding End_date on Cur_working*/
	$("#cur_working").change(function() {
		if(this.checked){
			$("#end_date").hide();
			$("#end_date").removeProp('required');

		}else{
			$("#end_date").show();
			$("#end_date").prop('required');
		}
	});

	/* Add Rows */
	$('#addMoreRows').click(function(event){
		event.preventDefault();
				
				rowCount++; 
				var recRow = '<div id="added_field'+rowCount+'"><div class="row"><div class="col-md-12"><h3>Add Experience</h3><div class="row"><div class="col-md-4"><input id="org_name'+rowCount+'" type="text" name="org_name[]" class="form-control" placeholder="Organisation Name.."  required></div><div class="col-md-5"><input id="title'+rowCount+'" type="text" name="title[]" class="form-control" placeholder="Profile Title.." required></div></div></div></div><div class="row"><div class="col-md-12"><div class="row"><div class="col-md-4"><input id="location'+rowCount+'"type="text" name="location[]" class="form-control" placeholder="Location" required></div><div class="col-md-2"><input id="start_date'+rowCount+'" type="text" name="start_date[]" class="form-control" placeholder="Start Date" onclick="startDate('+rowCount+');" required></div><div class="col-md-2"><input id="end_date'+rowCount+'" type="text" name="end_date[]" class="form-control" placeholder="End Date" onclick="endDate('+rowCount+');" required></div><div class="col-md-2"><input id="cur_working'+rowCount+'" type="checkbox" name="cur_working[]" onclick="hideEndDate('+rowCount+')">&nbsp;<label> Currently Working.</label></div></div></div></div><div class="row"><div class="col-md-12"><div class="row"><div class="col-md-4"><textarea id="about_me'+rowCount+'" class="form-control" name="about_me[]" rows="5" placeholder="About Me..." required></textarea></div><div class="col-md-3"><button id="removeRow'+rowCount+'" type="button" name="add_fields" class="btn btn-danger" onclick="removeRow('+rowCount+');" >Remove</button></div></div></div></div></div>';

				// Appending Div Element
				$('#addedRows').append(recRow);
	});

		// BootStrap DateTime Picker
		$('#start_date').datepicker();
		$('#end_date').datepicker();

});
/* Remove Rows */
function removeRow(rowNum){
		$('#added_field'+rowNum).remove();
}

function startDate(start){
	$('#start_date'+start).datepicker();
}
function endDate(end){
	$('#end_date'+end).datepicker();
}
function hideEndDate(endchk){
	$("#cur_working"+endchk).change(function() {
		if(this.checked){
			$("#end_date"+endchk).hide();
			$("#end_date"+endchk).removeProp('required');
		}
		else{
			$("#end_date"+endchk).show();
			$("#end_date").prop('required');	
		}
	});
}
</script> 
</body>
</html>
