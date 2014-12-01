<?PHP
include('private_config.php');

class ParticipantSummary {
    var $username;
    var $pwd;
    var $database;
    var $tablename;
    var $connection;
    var $db_host;

	function writeToFile($filename, $content) {
		$file = fopen($filename, "w");
		fwrite($file, $content);
		fclose($file); 
	}

	function summary($p_dbusername, $p_dbpassword, $p_dbname, $p_tablename, $p_rankTableName, $p_scoreTableName, $p_contestId, $p_hostname){
		$this->username = $p_dbusername;
		$this->pwd = $p_dbpassword;
		$this->database = $p_dbname;
		$this->tablename = $p_tablename;
		$this->rankTableName = $p_rankTableName;
		$this->scoreTableName = $p_scoreTableName;
		$this->db_host = $p_hostname;
	    $this->connection = mysql_connect($this->db_host,$this->username,$this->pwd);
	    $this->contestId = $p_contestId;

        if(!$this->connection)
        {
            return false;
        }
        if(!mysql_select_db($this->database, $this->connection))
        {
            return false;
        }
        if(!mysql_query("SET NAMES 'UTF8'",$this->connection))
        {
            return false;
        }
        $query = "select name,email,username from $this->tablename where username != 'admin' and email is not null"; 
	
		$result = mysql_query($query, $this->connection);
		
		$output = "Number of Problems Solved,Score,UserId,Name,E-mail Address\n";
		while ($row = mysql_fetch_array($result))
		{
			$this->teamID = $row['username'];
			$rankQuery = "select correct,totalTime from $this->rankTableName where teamid = '$this->teamID' and cid='$this->contestId'";
			$rankResource = mysql_query($rankQuery, $this->connection);
			$rankResultArray = mysql_fetch_array($rankResource);

			$rankValue = $rankResultArray['totalTime'];
			$totalSubmissions = 0;
			if($rankValue == "0" || $rankValue == "" || $rankResultArray==null)
			{
				$scoreQuery = "select submissions from $this->scoreTableName where teamid = '$this->teamID' and cid='$this->contestId'";
				$scoreResource = mysql_query($scoreQuery, $this->connection);
				$totalSubmissions = 0;

				while($scoreRow = mysql_fetch_array($scoreResource))
				{
					$totalSubmissions += $scoreRow['submissions'];
				}
				if($totalSubmissions == '0')
				{
					$rankValue = "";
					$rankResultArray['correct'] = "";
				}
				else
				{
					$rankValue = '0';
					$rankResultArray['correct'] = '0';
				}
			}

			$output = $output . "\"" . $rankResultArray['correct'] . "\",\"" . $rankValue . "\",\"" . $row['username'] . "\",\"" . $row['name'] . "\",\"" . $row['email'] . "\"\n";

		}

		$this->writeToFile("ParticipantSummary.csv", $output);		
	}	
}

$contestId = $argv[1];
$summaryBot = new ParticipantSummary();
$summaryBot->summary($p_dbusername, $p_dbpassword, $p_dbname, $p_tablename, $p_rankTableName, $p_scoreTableName, $contestId, $p_hostname);	
?>