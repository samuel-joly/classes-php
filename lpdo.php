<?php
	class lpdo
	{
		private $connexion = null;
		private $table = null;
		public $lastQuery = null;
		public $lastResult = null;
		
		function constructeur($host="", $username="", $password="", $db="")
		{
			$this->connexion = mysqli_connect($host, $username, $password, $db);
			$this->table = $db;
		}
		
		function connect($host="", $username="", $password="", $db="")
		{
			if(isset($this->connexion))
			{
				mysqli_close($this->connexion);
			}
			$this->connexion = mysqli_connect($host, $username, $password, $db);
			$this->table = $db;
		}
		
		function destructeur()
		{
			if(isset($this->connexion))
			{
				echo "LE DESTRUCTEUR A TERMINÉ SA MISSION";
				mysqli_close($this->connexion);
			}
		}
		
		function close()
		{
			if(isset($this->connexion))
			{
				mysqli_close($this->connexion);				
			}
		}
		
		function execute($query)
		{
			$this->lastQuery = $query;
			if(isset($this->connexion))
			{
				$return_data = [];
				$prepare = mysqli_query($this->connexion,$this->lastQuery);
				foreach (mysqli_fetch_all($prepare) as $data)
				{
					array_push($return_data, $data);
				}
				
				$this->lastResult = $return_data;
				return $return_data;
			}
			else
			{
				echo "pas de connexion <br>";
			}
		}
		
		function getLastQuery()
		{
			if(isset($this->lastQuery))
			{
				return $this->lastQuery;
			}
			else
			{
				return false;
			}
			
		}
		
		function getLastResult()
		{
			if(isset($this->lastResult))
			{
				return $this->lastResult;
			}
			else
			{
				return false;
			}
		}
		
		function getTables()
		{
			if(isset($this->connexion))
			{
				$table_list = [];
				foreach ($this->execute("SELECT TABLE_SCHEMA, TABLE_NAME
				FROM `information_schema`.`TABLES` WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_SCHEMA != 'mysql'
				AND TABLE_SCHEMA != 'performance_schema' AND TABLE_SCHEMA != 'sys'") as $result)
				{
					array_push($table_list, [$result[0], $result[1]]);
				}
				return $table_list;
			}
			else
			{
				echo "Pas de connexion<br>";
			}
			
		}
	}


	
	$lpdo = new lpdo();
	$lpdo->constructeur("localhost","root","","poo");
	var_dump($lpdo->getTables());
	var_dump($lpdo->getLastQuery());
	

?>