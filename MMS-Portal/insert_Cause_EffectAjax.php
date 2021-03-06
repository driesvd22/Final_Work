<?php
/**
 * Created by PhpStorm.
 * User: Dries
 * Date: 24/02/2019
 * Time: 13:31
 */

session_start();

include './Database/Forms/InsertCauseEffect/server.php';
include_once './Database/DAO/CauseEffectDB.php';
include_once './Database/DAO/CauseDB.php';
include_once './Database/DAO/EffectDB.php';
include_once './Database/DAO/ErrorDB.php';
include_once './Database/DAO/ClusterDB.php';



?>

<html style="height: 100%;overflow:hidden">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="Final Work">
    <meta name="author" content="Dries Van Dievoort & Stefanos Stoikos">
    <title>
        Final Work - MMS DB Acces
    </title>
<link rel="stylesheet" href="./CSS/Custom.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

</head>

<body style="height: 100%;overflow:hidden">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">Final Work - MMS DB Acces</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home
                        </a>
                    </li>
                    <li class="nav-item active">
                        <?php if(isset($_SESSION['login']) && $_SESSION['userType'] == 0){ ?>
                            <a class="nav-link" href="relations.php"><?php echo 'Relations'; ?><span class="sr-only">(current)</span></a>
                        <?php } ?>
                    </li>
                    <li class="nav-item">
                        <?php if(isset($_SESSION['login']) && $_SESSION['userType'] == 0){ ?>
                            <a class="nav-link" href="manage_status_effect.php"><?php echo 'Status Effect'; ?><span class="sr-only">(current)</span></a>
                        <?php } ?>
                    </li>
                    <li class="nav-item">
                        <?php if(isset($_SESSION['login']) && $_SESSION['userType'] == 0){ ?>
                            <a class="nav-link" href="manageUser.php"><?php echo 'User Management & Webservice'; ?></a>
                        <?php } ?>
                    </li>
                    <li class="nav-item">
                        <?php if (isset($_SESSION['login'])){ ?>
                        <a class="nav-link" href="index.php?logout='1'">Logout</a>
                        <?php }
                        else{ ?>
                        <a class="nav-link" href="login.php">Login</a>
                        <?php }?>

                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <br>
    <br>
    <br>

    <div class="container" style="width: 50%; float: left; height: 80%;">
        <h1>Insert Cause - Effect</h1>
        <?php if (isset($_GET['idEffect'])) {
            $deIdvoorDriesMijnKapoentje = $_GET['idEffect'];
        }else {
            $deIdvoorDriesMijnKapoentje = 0;
        }
        ?>
        <form method="post" action="insert_Cause_Effect.php">
            <h2>Causes</h2>
            <div class="wrap" style="height: 25px">
                <div class="search">
				    <div class="input-group">
					   <input type="text" class="searchTerm" name="search_text" id="search_text" placeholder="Filter Causes" class="form-control" />
				    </div>
			    </div>
            </div>
    <br />
    <div id="result" class="container" style="float: left; overflow: auto; height: 20%; margin-top: 8px; margin-bottom: 8px"></div>
            
            <h2>Effects</h2>

            <div class="wrap" style="height: 25px">
                <div class="search">
				    <div class="input-group">
					   <input type="text" class="searchTerm" name="search_textEffect" id="search_textEffect" placeholder="Filter Effects" class="form-control" />
				    </div>
			    </div>
            </div>
            <br/>
            <div id="resultEffect" class="container" style="float: left; overflow: auto; height: 20%; margin-top: 8px; margin-bottom: 8px"></div>
        
            <button type="submit" class="btn btn-success" name="insert_CauseEffect" style="margin-top: 8px">Insert</button>
        </form>
        
    </div>

        <div class="container" style="overflow: auto; height: 80%; width: 50%; float: left">
            <h1>Cause - Effect</h1>
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Cause</th>
                        <th>Effect</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                <?php $causeEffects = CauseEffectDB::getAll();
                for ($e = 0; $e < count($causeEffects); $e++){ ?>
                    <tr>
                        <td><?php echo $e + 1?></td>
                        <td><?php $cause = CauseDB::getById($causeEffects[$e]->Cause_idCause); echo $cause[0]->CauseName; ?></td>
                        <td><?php $effect = EffectDB::getById($causeEffects[$e]->Effect_idEffect); echo $effect[0]->EffectName;  ?></td>
                        <td>
                            <form method="post" action="insert_Cause_Effect.php">
                                <input type="hidden" value="<?php echo $causeEffects[$e]->id?>" name="Delete_causeEffect_id">
                                <button type="submit" class="btn btn-danger" name="Delete_causeEffect"><i class="fa fa-trash" style="font-size: 20px;"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    


<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="Bootstrap/js/bootstrap.min.js"></script>
               <script src="Bootstrap/jquery-3-3-1.js"></script>
    <script src="Bootstrap/js/bootstrap.js"></script>
            
<script>
$(document).ready(function(){
	load_data();
	function load_data(query)
	{
		$.ajax({
			url:"fetch.php",
			method:"post",
			data:{query:query},
			success:function(data)
			{
				$('#result').html(data);
			}
		});
	}
	
	$('#search_text').keyup(function(){
		var search = $(this).val();
		if(search != '')
		{
			load_data(search);
		}
		else
		{
			load_data();			
		}
	});
});
</script>
            
<script>
$(document).ready(function(){
	load_data();
	function load_data(queryEffect)
	{
		$.ajax({
			url:"fetchInsertCauseEffect.php",
			method:"post",
			data:{queryEffect:queryEffect},
			success:function(data)
			{
				$('#resultEffect').html(data);
			}
		});
	}
	
	$('#search_textEffect').keyup(function(){
		var search = $(this).val();
		if(search != '')
		{
			load_data(search);
		}
		else
		{
			load_data();			
		}
	});
});
</script>

</body>

</html>
