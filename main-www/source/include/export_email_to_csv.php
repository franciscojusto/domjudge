<?PHP
include('private_config.php');

class EmailExporter {
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

		function exportEmails($p_dbusername, $p_dbpassword, $p_dbname, $p_tablename, $p_hostname){
			$this->username = $p_dbusername;
			$this->pwd = $p_dbpassword;
			$this->database = $p_dbname;
			$this->tablename = $p_tablename;
			$this->db_host = $p_hostname;
		        $this->connection = mysql_connect($this->db_host,$this->username,$this->pwd);
	
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
		        $query = "select name,email from $this->tablename where username != 'admin' and email is not null"; 
			
			$result = mysql_query($query, $this->connection);
			
			$output = "First Name,E-mail Address\n";
			while ($row = mysql_fetch_array($result))
			{
				$output = $output . "\"" . $row['name'] . "\",\"" . $row['email'] . "\"\n";
			}
	
			$this->writeToFile("registered_users.csv", $output);		
		}	
}
	
	$exporter = new EmailExporter();
	$exporter->exportEmails($p_dbusername, $p_dbpassword, $p_dbname, $p_tablename, $p_hostname);	
?>
