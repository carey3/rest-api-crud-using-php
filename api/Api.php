<?php

//Api.php

require_once('../lib/utilities.php');

class API
{
	private $connect = '';

	public function __construct()
	{
                simpleLog('Api.php __construct() <');
		$this->database_connection();
	}

	public function database_connection()
	{
                simpleLog('Api.php database_connection() ');
	        try {  
		   $this->connect = new PDO("mysql:host=localhost;dbname=testing", "rw", "PassWord1");
		} catch (Exception $e) {
			$msg = ' exception :'.$e->getMessage();
                        simpleLog('Api.php database_connection()  <'.$msg );
			$fn = "simpleLog.log";
			$fp = fopen($fn,"a+");
			fwrite($fp,"$msg\n");
			fclose($fp);

	   	    echo 'Caught exception: ',  $e->getMessage(), "\n";
	        }  
	}

	public function fetch_all()
	{
		$query = "SELECT * FROM tbl_sample ORDER BY id \n";
                simpleLog('Api.php fetch_all() '.$query);
	        $msg = 'fetch_all :'.$query;
	        try {  
		   $statement = $this->connect->prepare($query);
	        } catch (Exception $e) {
	   	    $msg .= '  exception :'.$e->getMessage();
	   	    echo 'Caught exception: ',  $e->getMessage(), "\n";
                    simpleLog('Api.php fetch_all() '.$msg);
	        }  
		if($statement->execute())
		{
			while($row = $statement->fetch(PDO::FETCH_ASSOC))
			{
				$data[] = $row;
			}
			$msg .= '  done ';
                        simpleLog('Api.php fetch_all() '.$msg);
			return $data;
		}
	}

	public function insert()
	{
		$msg = ' starts : ';
                simpleLog('Api.php insert() '.$msg);

		if(isset($_POST["first_name"]))
		{
			$first_name = $_POST["first_name"];
			$last_name  = $_POST["last_name"];
		        $msg = ' first - last name:'.$first_name.' '.$last_name.' \n';
			$form_data  = array(
				':first_name'		=>	$_POST["first_name"],
				':last_name'		=>	$_POST["last_name"]
			);
			$query = "
			INSERT INTO testing.tbl_sample 
			(`first_name`, `last_name`) 
			 VALUES 
                        (:first_name,:last_name);
			";
			//(\"".$first_name."\", \"".$last_name."\");
		        $msg = '   insert query :'.$query;
                        simpleLog('Api.php insert() '.$msg);
			$statement = $this->connect->prepare($query);
			if($statement->execute($form_data))
			{
				$data[] = array(
					'success'	=>	'1'
				);
		                $msg .= '  successful - ';
			}
			else
			{
				$data[] = array(
					'success'	=>	'0'
				);
		                $msg .= '  not successfull -- ';
			}
		}
		else
		{
			$data[] = array(
				'success'	=>	'0'
			);
	        	$msg .= '  not successfull - ';
		}

		$msg = '   done : ';
                simpleLog('Api.php insert() '.$msg);
		return $data;
	}

	public function fetch_single($id)
	{
		$query = "SELECT * FROM tbl_sample WHERE id='".$id."'";
		simpleLog('Api.php fetch_single start - query: '.$query );
		$statement = $this->connect->prepare($query);
		if($statement->execute())
		{
			foreach($statement->fetchAll() as $row)
			{
				$data['first_name'] = $row['first_name'];
				$data['last_name'] = $row['last_name'];
			}
		        simpleLog('fetch_single done' );
			return $data;
		}
	}

	public function update()
	{
		simpleLog('Api.php - update start');
		if(isset($_POST["first_name"]))
		{
			$form_data = array(
				':first_name'	=>	$_POST['first_name'],
				':last_name'	=>	$_POST['last_name'],
				':id'		=>	$_POST['id']
			);
			$query = "
			UPDATE tbl_sample 
			SET first_name = :first_name, last_name = :last_name 
			WHERE id = :id
			";
		        simpleLog('Api.php - update start  '.$query );
			$statement = $this->connect->prepare($query);
			if($statement->execute($form_data))
			{
				$data[] = array(
					'success'	=>	'1'
				);
		                $msg = '  success ';
			}
			else
			{
				$data[] = array(
					'success'	=>	'0'
				);
	        	        $msg = '   not successfull - ';
			}
		}
		else
		{
			$data[] = array(
				'success'	=>	'0'
			);
	       	        $msg = '   not successfull --- ';
		}
	        simpleLog('update done  '.$msg );
		return $data;
	}
	public function delete($id)
	{
		$query = "DELETE FROM tbl_sample WHERE id = '".$id."'";
		$statement = $this->connect->prepare($query);
		simpleLog('Api.php - delete start query - '.$query );
		if($statement->execute())
		{
			$data[] = array(
				'success'	=>	'1'
			);
		        $msg = '  success ';
		}
		else
		{
			$data[] = array(
				'success'	=>	'0'
			);
	                $msg = '   not successfull - ';
		}
	        simpleLog('Api.php -  delete done  '.$msg );
		return $data;
	}
}

?>
