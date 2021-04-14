<?php session_start(); ?>
<html>
<head>
<title>MediTrack</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="Visitors Responsive web template, Bootstrap Web Templates, Flat Web Templates, Android Compatible web template, 
Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyEricsson, Motorola web design" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<!-- bootstrap-css -->
<link rel="stylesheet" href="css/bootstrap1.min.css" >
<!-- //bootstrap-css -->
<!-- Custom CSS -->
<link href="meditrack.css" rel='stylesheet' type='text/css' />
<!-- <link href="css/style-responsive.css" rel="stylesheet"/> -->
<!-- font CSS -->
<link href='//fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>
<!-- font-awesome icons -->
<!-- <link rel="stylesheet" href="css/font.css" type="text/css"/> -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<!-- //font-awesome icons -->
<script src="js/jquery2.0.3.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Counter-Up/1.0.0/jquery.counterup.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/waypoints/4.0.1/jquery.waypoints.js"></script>
</head>
<body>
<section id="container">
    <!--header start-->
    <header class="header fixed-top clearfix">
    <!--logo start-->
    <div class="brand">
        <a href="./" class="logo">
            meditrack</a>
        <div class="sidebar-toggle-box">
            <i class="fa fa-bars" aria-hidden="true"></i>
        </div>
    </div>
    <!--logo end-->

    <div class="top-nav clearfix">
        <!--search & user info start-->
        <ul class="nav pull-right top-menu">
            <!-- user login dropdown start-->
            <li class="dropdown">
                <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                    <!-- <img alt="" src="img/logo.png" style="padding: 4px; width: 40px;height: 40px;"> -->
                    <b class="caret"></b>
                </a>
                <ul class="dropdown-menu extended logout">
                    <li><a href="#"><i class="fa fa-key"></i>Change Password</a></li>
                    <li><a href="#"><i class="fa fa-key"></i>Log Out</a></li>
                </ul>
            </li>
            <!-- user login dropdown end -->
        </ul>
        <!--search & user info end-->
    </div>
    </header>
<?php

    include 'database.php';
    require_once 'functions.php';
	
	$config=read_config();
	$chain=@$_GET['chain'];
	if (strlen($chain))
		$name=@$config[$chain]['name'];
	else
		$name='';

	// set_multichain_chain($config[$chain]);
    
    $con = connect();
    $email = $_POST['email'];
    
    $query = "select password from users where email='$email'";
    $result = mysqli_query($con, $query);
    
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
        mysqli_free_result($result);
        if($_POST['password'] == $row["password"])
        {
            $_SESSION[$row['name']] = time();
            
            foreach ($config as $chain => $rpc)
                if (isset($rpc['rpchost']))
                    echo '<p class="lead" style="margin-top:60px;"><a href="./?chain='.html($chain).'">'.html($rpc['name']).'</a><br/>';

        }
        else{
            echo '<script>alert("email or password is incorrect")</script>';
            sleep(5);
            header("Location: index.php");
        }
    } else{
        echo "No records matching your query were found.";
    }  
    // function hasher(){
    //     $h = hash('sha256',$name);
    //     return $h;
    // }
    closeConnection($con);
?>
</section>
<script src="js/bootstrap.js"></script>
<script src="js/jquery.dcjqaccordion.2.7.js"></script>
<script src="js/scripts.js"></script>
<script src="js/jquery.slimscroll.js"></script>
<script src="js/jquery.nicescroll.js"></script>
<script src="js/jquery.scrollTo.js"></script>
</body>
</html>