<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Results - Ultimate Software Programming Competition, Win a Trip to South Beach</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<link rel="stylesheet" href="https://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
	<link href='https://fonts.googleapis.com/css?family=Average' rel='stylesheet' type='text/css'>
	<script src="jquery-1.10.2.js"></script>
	<script src="jqueryui/js/jquery-ui-1.10.3.custom.js"></script>		
	<script>
		$(function() {
		 	$( "#accordion" ).accordion({collapsible: true});
			$( "#inner_accordion" ).accordion({collapsible: true});
		 	$( ".selector" ).accordion( "option", "icons", { "header": "ui-icon-plus", "activeHeader": "ui-icon-minus" } );
			    var icons = {
			      header: "ui-icon-circle-arrow-e",
			      activeHeader: "ui-icon-circle-arrow-s"
			    };
			    $( "#accordion" ).accordion({ icons: icons });
			  });
	</script>
</head>
<body>
<div id="strip"></div>
<div id="container">
<?php include("topnav.html"); ?>
 
<fieldset>
<legend>Previous Competitions</legend>
     
	<div class="content">
		  <div id="accordion">
			  	<?php 
				chdir('CONTESTS');
				$TAB = "&nbsp;&nbsp;&nbsp;";
				//4 files per problem : input, output, pdf, and result
				//find number of folders that contain "P_R_" in the name. 
				//for every one of those folders, the contents will be listed as links  
				//dirs : contains all directories in the current directory
				$dirs = array_filter(glob('*'), 'is_dir');

				

				
				//sort $dirs to be backwards so that the most recent contest shows up first
				arsort($dirs);
				
				
				
				foreach ($dirs as &$like)
				{
				
				
					//EACH CONTEST
					//problem(.pdf), solution(.cpp, .txt), input(.in), output(.out)
					if (strpos($like,'P_R') !== false) 
					{
						

					
						$problem_folders = scandir($like); 
						$output = ""; 
						//get list of problem_folders within current folder 
						for($k=2; $k < count($problem_folders); $k++)
							{ 
								//EACH PROBLEM  
								$problem_files = scandir($like."/".$problem_folders[$k]);  
								
								for($j = 2; $j < count($problem_files); $j++)
								{
									
									if(strpos($problem_files[$j],'.pdf')!== false )
											{ 
												$temp = $problem_files[2];
												$problem_files[2]  = $problem_files[$j];
												$problem_files[$j] = $temp;  
											}
									//it looks solutions by 'SOLUTION' given the variety of extensions
									else if(strpos($problem_files[$j],'SOLUTION')!== false  ) 
											{
												$temp = $problem_files[3];
												$problem_files[3]  = $problem_files[$j];
												$problem_files[$j] = $temp; 
											}
									else if(strpos($problem_files[$j],'.in') !== false )
											{
												$temp = $problem_files[4];
												$problem_files[4]  = $problem_files[$j];
												$problem_files[$j] = $temp; 
											}
									else if(strpos($problem_files[$j],'.out') !== false  )
											{ 
												$temp = $problem_files[5];
												$problem_files[5]  = $problem_files[$j];
												$problem_files[$j] = $temp; 
											}  
									else if(strpos($problem_files[$j],'.txt') !== false )
											{ 
												$temp = $problem_files[7];
												$problem_files[7]  = $problem_files[$j];
												$problem_files[$j] = $temp; 
											}  
								}
								
								$output = $output."<p><div style=\"width:190px;float:left;\"> ".$problem_folders[$k]." : </div></p>"; 
									
									//OUTPUTS FILES WITHIN THE PROBLEM FOLDERS
									for($j=2; $j < count($problem_files);$j++)
									{
										$cur_file = explode(".",$problem_files[$j]); 
										
										if($problem_files[$j] != '')
											{
											$output = $output.$TAB;
											}
											
										$output = $output."<a href=\"CONTESTS/".$like."/".$problem_folders[$k]."/".$problem_files[$j]."\" target=\"_break\">".$cur_file[0]."</a>"; 
										 
									}
									
								$output = $output."<br/><br/>";
								 
							} 
						/*
						-Editing string
						-Splitting based on '_' 
						*/
						$folder_name = explode("_", $like);
						  
						//ECHO statements
							echo "<h3>".$folder_name[3]." ".$folder_name[4].", ".$folder_name[5]."</h3>
									<div>
										<p>";  
										
							echo $output;
										
										
											 
							echo  " 	</p> 
									</div>"; 
					}
				}
				?>
				
		  </div>  
	</div>


	
</div>
</fieldset>



</div>

<?php include_once("analyticstracking.php") ?>
</body>
</html>
