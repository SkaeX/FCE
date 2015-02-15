<?php
include_once '../includes/db_connect.php';
include_once '../includes/functions.php';
if (isset($_POST['sbmt_semester'])) {
    $semester = $_POST['semester'];
    $crn_array = array();
    $course_code_array = array();
    $email = $_SESSION['email'];   
    $result = $mysqli->query("SELECT crn, course_code from section where faculty_email='$email' and semester ='$semester'"); 

    for($i = 0; $i < $result->num_rows; $i++) {
        $row = $result->fetch_assoc();
        array_push($crn_array, $row['crn']);
        array_push($course_code_array, $row['course_code']);
    }
}
if (isset($_POST['submit'])) {
    $_SESSION['eval_type'] = $eval_type = $_POST['eval_type'];
    $_SESSION['crn'] = $_POST['crn'];
    header("Location: $eval_type" . "_report.php");
    
}
?>
<!DOCTYPE HTML>
<html>
<head>
<title>FCE Faculty</title>
<!-- Bootstrap -->
<link href="../css/bootstrap.min.css" rel='stylesheet' type='text/css' />
<link href="../css/bootstrap.css" rel='stylesheet' type='text/css' />
<link href="../css/style.custom.css" rel='stylesheet' type='text/css' />
<meta name="viewport" content="width=device-width, initial-scale=1">
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
 <!--[if lt IE 9]>
     <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
     <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
<link href="../css/style.css" rel="stylesheet" type="text/css" media="all" />
<!-- start plugins -->
<script type="text/javascript" src="../js/jquery.min.js"></script>
<script type="text/javascript" src="../js/bootstrap.js"></script>
<script type="text/javascript" src="../js/bootstrap.min.js"></script>
<!--font-Awesome-->
   	<link rel="stylesheet" href="../fonts/css/font-awesome.min.css">
<!--font-Awesome-->
</head>
<body>
<div class="header_bg1">
<div class="container">
	<div class="row header">
		<div class="logo navbar-left">
			<h1><a href="../index.php">Faculty Course Evaluation</a></h1>
		</div>
		
	</div>
	<div class="row h_menu">
		<nav class="navbar navbar-default navbar-left" role="navigation">
		    <!-- Brand and toggle get grouped for better mobile display -->
		    <div class="navbar-header">
		      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
		        <span class="sr-only">Toggle navigation</span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		      </button>
		    </div>
		    <!-- Collect the nav links, forms, and other content for toggling -->
		    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
		      <ul class="nav navbar-nav">
                <li><a>Faculty</a></li>
                <?php
                $semester = getCurrentSemester();
                $school = $_SESSION['school'];
                $name = $_SESSION['name'];
                echo "<li><a>$semester</a></li>";
                echo "<li><a>$school</a></li>";
                echo "<li><a>$name</a></li>";
                ?>
                </ul>
		    </div>
		</nav>
	</div>
</div>
</div>
<div class="main_bg"><!-- start main -->
	<div class="container">
		<div class="main row">
			<div class=" blog_left">
				
			<div class=" blog_right text-center">
				<?php 
                if (!(isset($_POST['sbmt_semester']))) {
                ?>
    				<form action="" method="post">
                        <?php

                        echo '<select name="semester" class="input-sm" required>';
                        echo '<option selected value="">--Choose Semester--</option>';
                        $result = $mysqli->query("SELECT semester from semester");
                        for($i = 0; $i < $result->num_rows; $i++) {
                            $row = $result->fetch_assoc();
                            echo "<option value='$row[semester]'>$row[semester]</option>";
                        }
                        echo '</select>';
                        ?>
                        <div class="clearfix"></div>
                        <button class="fa-btn btn-1 btn-1e" name='sbmt_semester'>SUBMIT</button>
                    </form>
                <?php
                }
                ?>

                <?php 
                if (isset($_POST['sbmt_semester'])) {
                ?>
                    <form action="" method="post">

                       <div style="height:25px"></div>
    					<select name="eval_type" class="input-sm" required>
    	                    <option selected value="">--Choose Evaluation Type--</option>
    	                    <option value="mid">Midterm</option>
    	                    <option value="final">Final</option>
    	                </select>

    					<div style="height:25px"></div>
                        <?php
    					echo '<select name="crn" class="input-sm" required>';
                        echo "<option selected value=''>--Choose course--</option>";
                        $i = 0;
                        while($i < count($course_code_array)) {
    	                    echo "<option value='$crn_array[$i]'>$course_code_array[$i] - $crn_array[$i]</option>";
                            $i++;
                        }
                        echo '</select>';
                        ?> 
    					<div class="clearfix"></div>
                        <button class="fa-btn btn-1 btn-1e" name='submit'>SUBMIT</button>
    				</form>
                <?php
                }
                ?>
			</div>	
			</div>
		</div>
	</div>
</div><!-- end main -->
<div class="footer_bg"><!-- start footer -->
	<div class="container">
		<div class="row  footer">
			<div class="copy text-center">
				<p class="link"><span>&#169; All rights reserved | Design by&nbsp;<a href="http://w3layouts.com/"> W3Layouts</a></span></p>
			</div>
		</div>
	</div>
</div>
</body>
</html>