<?PHP

include('private_config.php');

class PostEventReport {
	var $database;
	var $db_host;	
	var $username;
	var $pwd;
	var $rankTable;
    var $scoreTable;
	var $contestId;
	var $topContestants;
	var $connection;

	function PostEventReport($database, $hostname, $username, $pwd, $rankTable, $scoreTable, $contestId, $topContestants=10)
	{
		$this->database = $database;
		$this->db_host = $hostname;
		$this->username = $username;
		$this->pwd = $pwd;
		$this->rankTable = $rankTable;
        $this->scoreTable = $scoreTable;
		$this->contestId = $contestId;
		$this->topContestants = $topContestants;
		$this->connection = mysql_connect($this->db_host, $this->username, $this->pwd);
	}
	
	function writeToFile($filename, $content) {
		$file = fopen($filename, "w");
		fwrite($file, $content);
		fclose($file);
	}
	
	function generateReport(){
		$output = '<html><head>';
        $output .= '<link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.6.0/pure-min.css"></head><body>';
		$output .= $this->getTopContestants();
		$output .= $this->getAllUserWithOneProblemSolved();
        $output .= '</body></html>';
        $this->writeToFile("Report.html", $output);
	}
	
	function getTopContestants() {
		
		if(!$this->connection || !mysql_select_db($this->database, $this->connection) || !mysql_query("SET NAMES 'UTF8'", $this->connection)){
			return "Database Connection failed!";
		}
		
		$output = '<h2>Contestants with Top 10 Scores</h2>';
		$output .= '<table class="pure-table"><thead><tr>';
        // Headers
        $output .= '<th>Rank</th>';
        $output .= '<th>Name</th>';
        $output .= '<th>Email</th>';
		$output .= '<th>Location</th></tr></thead><tbody>';
		
        $query = "SELECT user.name, user.email, user.ip_address FROM user INNER JOIN $this->rankTable ON user.teamid=$this->rankTable.teamid ";
		$query .= "WHERE $this->rankTable.cid=$this->contestId ORDER BY $this->rankTable.correct DESC, $this->rankTable.totaltime LIMIT $this->topContestants;";
		
		$result = mysql_query($query, $this->connection);
		$rank = 1;
		while($row = mysql_fetch_array($result))
		{
			$teamid = $row['name'];
			$email = $row['email'];
            // Get location from ip using GeoPlugin API
            $ip = $row['ip_address'];
            $response = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip='.$ip));
            if ($response)
            {
                $city = $response['geoplugin_city'].', ';
                $region = $response['geoplugin_regionName'].', ';
                $country = $response['geoplugin_countryName'];
            }
			$output .= '<tr><td>'.$rank.'</td><td>'.$teamid.'</td><td>'.$email.'</td><td>'.$city.$region.$country.'</tr>';
			$rank++;
		}
		$output .= '</tbody></table>';
		return $output;
	}

    function getAllUserWithOneProblemSolved()
    {
        if(!$this->connection || !mysql_select_db($this->database, $this->connection) || !mysql_query("SET NAMES 'UTF8'", $this->connection)){
			return "Database Connection failed!";
		}

        $output = '<h2>Contestants with at least One Completed Problem</h2>';
        $output .= '<table class="pure-table"><thead><tr>';
        $output .= '<th>Name</th>';
        $output .= '<th>Email</th>';
        $output .= '<th>Number of <br>Completed Problem</th>';
        $output .= '<th>Completed Problems</th></tr></thead><tbody>';

        $getUsersQuery = "SELECT user.name, user.email from user INNER JOIN  $this->rankTable on user.teamid=$this->rankTable.teamid ";
        $getUsersQuery .= "WHERE $this->rankTable.cid=$this->contestId AND $this->rankTable.correct>=1;";
       
        while ($user = mysql_fetch_array(mysql_query($getUsersQuery, $this->connection)))
        {
            $name = $row['name'];
            $email = $row['email'];
            
            $getCompletedProblemsQuery = "SELECT probid FROM $this->scoreTable WHERE cid=$this->contestId AND teamid=$name AND iscorrect=1;";
            $completedProblems = mysql_fetch_array(mysql_query($getCompletedProblemsQuery, $this->connection));
            
            $output .= "<tr><td>$name</td><td>$email</td><td>".count($completedProblems)."</td><td>";
            foreach($completedProblems as $problem) {
                $output .= $problem.', ';
            }
            $output .= '</td></tr>';
        }       
        $output .= '</tbody></table>';
        return $output;
    }
}

$contestId = $argv[1];
$report = new PostEventReport($p_dbname, $p_hostname, $p_dbusername, $p_dbpassword, $p_rankTableName, $p_scoreTableName, $contestId);
$report->generateReport()
?>
