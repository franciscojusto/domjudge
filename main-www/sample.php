<?php

function genLinkName($extension)
{
    switch ($extension) {
        case "c":
            return "C"; break;
        case "cs":
            return "CSharp"; break;
        case "java":
            return "Java"; break;
        case "rb":
            return "Ruby"; break;
        case "py":
            return "Python"; break;
        case "pdf":
            return "PROBLEM"; break;
        case "in":
            return "INPUT"; break;
        case "out":
            return "OUTPUT"; break;
        default:
            return "";
    }
}

$output = "<div style=\"overflow:hidden; margin-bottom:20px\">";
$parentDir = "./SAMPLE";
//contains sample problems
$dirs = array_filter(glob($parentDir.'/*'), 'is_dir');

foreach($dirs as $problem)
{
    $output = $output.'<table style="table-layout:fixed"><col width="200px"><col width="10px"><col><tr><td><b>'.basename($problem).' : </b></td><td style="width:40px"></td>';
    $problem_files = scandir($problem);
 	
	for($j = 2; $j < count($problem_files); $j++)
	{
		if(strpos($problem_files[$j],'.pdf')!== false )
		{ 
	    	$temp = $problem_files[2];
			$problem_files[2]  = $problem_files[$j];
		    $problem_files[$j] = $temp;  
		}
		else if(strpos($problem_files[$j],'.in') !== false )
		{
			$temp = $problem_files[3];
			$problem_files[3]  = $problem_files[$j];
		    $problem_files[$j] = $temp; 
		}
		else if(strpos($problem_files[$j],'.out') !== false  )
		{ 
			$temp = $problem_files[4];
		    $problem_files[4]  = $problem_files[$j];
			$problem_files[$j] = $temp; 
		}  
		else if(strpos($problem_files[$j],'.c') !== false )
		{ 
			$temp = $problem_files[5];
			$problem_files[5]  = $problem_files[$j];
			$problem_files[$j] = $temp; 
		} 
        else if(strpos($problem_files[$j],'.cs') !== false )
		{ 
			$temp = $problem_files[6];
			$problem_files[6]  = $problem_files[$j];
			$problem_files[$j] = $temp; 
		}  
        else if(strpos($problem_files[$j],'.java') !== false )
        { 
            $temp = $problem_files[7];
            $problem_files[7]  = $problem_files[$j];
            $problem_files[$j] = $temp; 
        }  
        else if(strpos($problem_files[$j],'.py') !== false )
        { 
            $temp = $problem_files[8];
            $problem_files[8]  = $problem_files[$j];
            $problem_files[$j] = $temp; 
        }  
        else if(strpos($problem_files[$j],'.rb') !== false )
		{ 
			$temp = $problem_files[9];
			$problem_files[9]  = $problem_files[$j];
			$problem_files[$j] = $temp; 
		} 
        else if(strpos($problem_files[$j],'.zip') !== false )
        {
            $zip = $problem_files[$j];
        }
	}
	   
    $output = $output.'<td>'; 
 	//OUTPUTS FILES WITHIN THE PROBLEM FOLDERS
	for($j=2; $j < count($problem_files);$j++)
	{
		$cur_file = explode(".",$problem_files[$j]); 
		
	    if($problem_files[$j] != '')
		{
		    $output = $output."   ";
		}

        if($j == 5)
        {
            $output = $output.'</td><tr><td><b>SOLUTION : </b></td><td style="width:40px"></td><td>';
        }
											
		$output = $output.'<a style="margin:5px" href="'.$problem.'/'.$problem_files[$j].'" target="_blank">'.genLinkName($cur_file[1]).'</a>'; 
										 
	}
    $output = $output.'<a style="margin:5px" href="'.$problem.'/'.$zip.'">All Files</a>';
    $output = $output.'</td></tr>'; 
}

$output = $output."</table></div>";

echo $output;

?>
