<?php
include_once '../includes/db_connect.php';
include_once '../includes/functions.php';

checkUser("faculty");

if (isset($_POST['sbmt_semester'])) {
    $semester = $_POST['semester'];
    $crn_array = array();
    $course_code_array = array();
    $email = $_SESSION['email']; 
    $_SESSION['eval'] = $eval_type = $_POST['eval_type'];
    if ($eval_type == 'mid') {
        $result = $mysqli->query("SELECT crn, course_code from section where faculty_email='$email' and semester ='$semester' and mid_evaluation = 1");
    }  else {
        $result = $mysqli->query("SELECT crn, course_code from section where faculty_email='$email' and semester ='$semester' and final_evaluation = 1");
    }
     
    for($i = 0; $i < $result->num_rows; $i++) {
        $row = $result->fetch_assoc();
        array_push($crn_array, $row['crn']);
        array_push($course_code_array, $row['course_code']);
    }
}
    if (isset($_POST['submit'])) {
        $eval = $_SESSION['eval'];
        header("Location: $eval" . "_report.php?crn=$_POST[crn]");
        
    }
?>
<!DOCTYPE HTML>
<html>
<head>

<!--Favicon Kini -->
        <link rel="apple-touch-icon" sizes="57x57" href="../images/favicons/apple-touch-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="../images/favicons/apple-touch-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="../images/favicons/apple-touch-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="../images/favicons/apple-touch-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="../images/favicons/apple-touch-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="../images/favicons/apple-touch-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="../images/favicons/apple-touch-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="../images/favicons/apple-touch-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="../images/favicons/apple-touch-icon-180x180.png">
        <link rel="icon" type="image/png" href="../images/favicons/favicon-32x32.png" sizes="32x32">
        <link rel="icon" type="image/png" href="../images/favicons/android-chrome-192x192.png" sizes="192x192">
        <link rel="icon" type="image/png" href="../images/favicons/favicon-96x96.png" sizes="96x96">
        <link rel="icon" type="image/png" href="../images/favicons/favicon-16x16.png" sizes="16x16">
        <link rel="manifest" href="../images/favicons/manifest.json">
        <meta name="msapplication-TileColor" content="#da532c">
        <meta name="msapplication-TileImage" content="../images/favicons/mstile-144x144.png">
        <meta name="theme-color" content="#ffffff">

        <!-- End of Favicon Kini -->

<title>Faculty | Home</title>
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
		<div class="h_search navbar-right">
			<form action="../includes/logout.php" method="post">
				<button class='black-btn margin' name='logout' value='logout'>Logout</button>
			</form>
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
        <div class="main row para"> 
            <div class="col-xs-4 text-center"></div>        
            <div class="col-xs-4 text-center border">
				<?php 
                if (!(isset($_POST['sbmt_semester']))) {
                ?>
    				<form action="" method="post">
                        Please select semester and evaluation<br><br>
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

                       <div style="height:25px"></div>
                        <select name="eval_type" class="input-sm" required>
                            <option selected value="">--Choose Evaluation Type--</option>
                            <option value="mid">Midterm</option>
                            <option value="final">Final</option>
                        </select>
                        <br><br>
                        <button class="black-btn" name='sbmt_semester'>SUBMIT</button>
                    </form>
                <?php
                }
                ?>

                <?php 
                if (isset($_POST['sbmt_semester'])) {
                ?>
                    <form action="" method="post">

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
    					<br><br>
                        <button class="black-btn" name='submit'>SUBMIT</button>
    				</form>
                <?php
                }
                ?>
			</div>	
			</div>
                        <div class="text-center">
            
                <?php
                // if (isset($_POST['filter'])) {
                $result = $mysqli->query("SELECT * from section where semester = '$semester' 
                    AND faculty_email='$_SESSION[email]'");

                if (isset($_POST['filter'])) {

                    $sql = "SELECT * FROM section WHERE email='$_SESSION[email]'";
                    if (isset($_POST['semester']))
                        $sql .= " AND semester LIKE '%$_POST[semester]%'";

                    if (isset($_POST['search'])) {
                        $sql .= " AND course_code LIKE '%$_POST[search]%'";
                    }

                    $result = $mysqli->query($sql);
                }

                if ($result->num_rows == 0)
                    echo "<h4 class='error'>No section matches your criteria</h4>";
                else {
                    echo "<table width='100%' class='evaltable para dean_form not-center'>
                            <caption><h3>Reports</h3><hr></caption>
                        <thead>
                             <th>CRN</th>
                            <th>Course Code</th>
                            <th>Course Title</th>
                            <th>Enrolled</th>
                            <th>Midterm</th>
                            <th>Final</th>

                        </thead>";

                    
                    
                    for($i = 0; $i < $result->num_rows; $i++) {
                        $row = $result->fetch_assoc();
                        echo "<tr>";
                        echo "<td>$row[crn]</td>";
                        echo "<td>$row[course_code]</td>";
                        echo "<td>$row[course_title]</td>";
                        echo "<td>$row[enrolled]</td>";
                        
                        //echo "<td>$row[faculty_email]</td>";

                        if ($row['mid_evaluation'] == 0)
                            echo "<td>No Midterm Report</td>";
                        else
                            echo "<td><a target='_blank' href='mid_report.php?crn=$row[crn]'>View Midterm Report</a></td>";

                        if ($row['final_evaluation'] == 0)
                            echo "<td>No Final Report</td>";
                        else
                            echo "<td><a target='_blank' href='final_report.php?crn=$row[crn]'>View Final Report</a></td>";
                        
                        echo "</tr>";
                    }
                    echo '</tbody></table><hr>';
                }                
                ?>
            </div>
		</div>
	</div>
</div><!-- end main -->
<FOOTER>
        <div class="footer_bg"><!-- start footer -->
            <div class="container">
                <div class="row  footer">
                    <div class="copy text-center">
                        <p class="link"><span>&#169; All rights reserved | Design by&nbsp;<a href="thankyou.php#fceteam"> The FCE Team</a></span></p>
                    </div>
                </div>
            </div>
        </div>
    </FOOTER>
</body>
</html>