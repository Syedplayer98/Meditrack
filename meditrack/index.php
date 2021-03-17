<?php
	
	require_once 'functions.php';
	
	$config=read_config();
	$chain=@$_GET['chain'];
	
	if (strlen($chain))
		$name=@$config[$chain]['name'];
	else
		$name='';

    set_multichain_chain($config[$chain]);

    if(isset($_GET['logout'])){
        logout();
    }
    
?>
<html>
<head>
<title>MediTrack</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="Visitors Responsive web template, Bootstrap Web Templates, Flat Web Templates, Android Compatible web template, 
Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyEricsson, Motorola web design" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<link rel="stylesheet" href="css/bootstrap1.min.css" >
<link href="meditrack.css" rel='stylesheet' type='text/css' />
<link href='//fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="js/jquery2.0.3.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Counter-Up/1.0.0/jquery.counterup.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/waypoints/4.0.1/jquery.waypoints.js"></script>
</head>
<body>
<section id="container">
    <header class="header fixed-top clearfix">
    <div class="brand">
        <a href="./" class="logo">
            meditrack</a>
        <div class="sidebar-toggle-box">
            <i class="fa fa-bars" aria-hidden="true"></i>
        </div>
    </div>
    <div class="logoutbtn">
        <button class="w3dropbtn"><a style="color:white;" href="index.php?logout=true">Log Out</a></button>
    </div>
    </header>
    <?php
        if (strlen($chain)) {
            $name=@$config[$chain]['name'];
    ?>

	<aside>
        <div id="sidebar" class="nav-collapse">
            <div class="leftside-navigation">
                <ul class="sidebar-menu" id="nav-accordion">

                    <li>
                        <a href="./?chain=<?php echo html($chain)?>">
                            <span>Node</span>
                        </a>
                    </li>

                    <li>
                        <a href="./?chain=<?php echo html($chain)?>&page=permissions">
                            <span>Client Permissions</span>
                        </a>
                    </li>

                    <li>
                        <a href="./?chain=<?php echo html($chain)?>&page=issue">
                            <span>Issue Package</span>
                        </a>
                    </li>

                    <li>
                        <a href="./?chain=<?php echo html($chain)?>&page=update">
                            <span>Update Package</span>
                        </a>
                    </li>
                    
                    <li>
                        <a href="./?chain=<?php echo html($chain)?>&page=send">
                            <span>Send</span>
                        </a>
                    </li>

                    <li>
                        <a href="./?chain=<?php echo html($chain)?>&page=create">
                            <span>Create Stream</span>
                        </a>
                    </li>
                    
                    <li>
                        <a href="./?chain=<?php echo html($chain)?>&page=publish">
                            <span>Publish</span>
                        </a>
                    </li>

                    <li>
                        <a href="./?chain=<?php echo html($chain)?>&page=view">
                            <span>View Streams</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </aside>
    <?php
		switch (@$_GET['page']) {
			case 'label':
			case 'permissions':
			case 'issue':
			case 'update':
			case 'send':
			case 'offer':
			case 'accept':
			case 'create':
			case 'publish':
			case 'view':
			case 'approve':
            case 'asset-file':
				require_once 'page-'.$_GET['page'].'.php';
				break;
				
			default:
				require_once 'page-default.php';
				break;
		}
		
	    } else {
    ?>
    <div class="login">
        <section class="wrapper">
            <div class="table-agile-info">
                <div class="panel panel-default">
                    <div class="panel-heading" style="padding-top: 12px;">
                        <h2>  Login  </h2>
                    </div>
                </div>
                <form action="authentication.php" method="POST">
                    <div class="form-group row-width" style="margin-right: 50%;">
                        <p style="margin-top:10px;"><b>Please prove your identity:</b></p>
                        <div class=" row" style="margin-top:20px;">
                            <label class="col-lg-4">Email address:</label>
                            <input type="email" placeholder="abc@gmail.com" class="form-control col-lg-8" id="exampleInputEmail1" name="email">
                            <div class="text-danger"></div>
                        </div>
                    </div>
                    <div class="form-group row-width" style="margin-right: 50%;">
                        <div class="row">
                            <label class="col-lg-4">Password:</label>
                            <input type="password" class="form-control col-lg-8" id="exampleInputPassword1" name="password" placeholder="Password">
                            <div class="text-danger"></div>
                        </div>
                    </div>
                    <div class="row col-lg-7"><div style="padding-right:0px;" class="col-lg-2"><button type="submit" class="btn btn-primary">Log In</button></div><p style="padding-top:8px;" class="col-lg-9 text-muted"><small>Don't have an account?<a href="page-register.php"> Sign Up</a></small></p></div>
                </form>
            </div>
        </section>
        <div class="footer" style="margin-top: 50px"></div>
    </div>
    <?php
        }
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