<?php
	/*
		userpdo::register() 	= Get the user infos and send them to the Database
		userpdo::connect() 	= Save the database datas in the user object
		userpdo::disconnect() 	= Free object attributes 
		userpdo::delete()		= Free object attributes and delete users data from database
		userpdo::update()		= Change object data if entered other data than the one saved
		userpdo::isConnected() = Return true if the user id is not null, false if it is
		userpdo::getAllInfos() = Return all the stored data of the object
		userpdo::getLogin|Email|Firstname|Lastname() = Return the current object's attribute
		userpdo::refresh()		= Update object's attributes with database data
	*/

	class userpdo
	{
		private $id;
		public $login = null;
		public $email = null;
		public $firstname = null;
		public $lastname = null;
		
		public function register($login, $password, $email, $firstname, $lastname)
		{
			if(!empty($login) && !empty($password) && !empty($email) && !empty($firstname) && !empty($lastname))
			{			
				$pdo = new PDO("mysql:host=localhost;dbname=poo", "root", "");					
				$query = $pdo->query("SELECT * FROM utilisateurs WHERE login = '".$login."'");
				
				if(empty($query->fetch()))
				{
					$query = $pdo->query("INSERT INTO `utilisateurs`(`id`, `login`, `email`, `firstname`, `lastname`, `password`)
								VALUES (NULL, '".$login."', '".$email."','".$firstname."','".$lastname."','".password_hash($password,PASSWORD_BCRYPT)."')");
					if($query)
					{
						return [$login, $password, $email, $firstname, $lastname];
					}
					else
					{
						echo "Une erreure est survenue";
					}
				}
				else
				{
					echo "Pseudo déja pris";
				}
			}
		}
		
		public function connect($login, $password)
		{
			if(!empty($login) && !empty($password))
			{
				$pdo= new PDO("mysql:host=localhost;dbname=poo","root","");
				$usr_data= $pdo->query("SELECT * FROM utilisateurs WHERE login = '".$login."'")->fetch(PDO::FETCH_ASSOC);
				
				if(password_verify($password,$usr_data["password"]))
				{
					$this->id = $usr_data["id"];
					$this->login = $usr_data["login"];
					$this->email = $usr_data["email"];
					$this->firstname = $usr_data["firstname"];
					$this->lastname = $usr_data["lastname"];
					
					echo "Bienvenu ".$this->login." !<br>";
					return [$this->id, $this->login, $this->email, $this->firstname, $this->lastname];
				}
				else
				{
					echo "Mauvais mot de passe ou login <br>";
				}
			}
		}
		
		public function disconnect()
		{
			if(!is_null($this->id))
			{
				$this->id = null;
				$this->login = null;
				$this->email = null;
				$this->firstname = null;
				$this->lastname = null;
				echo "Au revoir !<br>";
			}
			else
			{
				echo "Vous n'etes pas connecté<br>";
			}
		}
		
		public function delete()
		{
			$pdo = new PDO("mysql:host=localhost;dbname=poo","root","");
			if($pdo->query("DELETE FROM `utilisateurs` WHERE `utilisateurs`.`id` = ".$this->id))
			{
				$this->disconnect();
				echo "Compte supprimé<br>";
			}
		}
		
		public function update($login , $email , $firstname, $lastname, $password)
		{	
			if(is_null($this->id))
			{
				return false;
			}
			
			$pdo = new PDO("mysql:host=localhost;dbname=poo","root","");
			$request = $pdo->prepare("UPDATE utilisateurs SET login = ?, email = ?, firstname = ?, lastname = ?, password = ?  WHERE id = ".$this->id);
			
			$column = "";
			$value = "";
			if(!isset($login))
			{
				$login = $this->login;
			}
			
			if(!isset($email))
			{
				$email = $this->email;
			}
			
			if(!isset($firstname))
			{
				$firstname=$this->firstname;
			}
			
			if(!isset($lastname))
			{
				$lastname = $this->lastname;
			}
			
			if(!isset($password))
			{
				echo "Vous devez rentrer un mot de passe<br>";
				return false;
			}
			
			$request->bindValue(1,$login);
			$request->bindValue(2,$email);
			$request->bindValue(3,$firstname);
			$request->bindValue(4,$lastname);
			$request->bindValue(5,password_hash($password, PASSWORD_BCRYPT));
			var_dump($request);
			
			if($login != $this->login && empty($pdo->query('SELECT login FROM utilisateurs WHERE login = "'.$login.'"')->fetch()[0]))
			{
				$request->execute();
			}
			
			if($email != $this->email)
			{
				$request->execute();
			}
			
			if($firstname != $this->firstname)
			{
				$request->execute();
			}
			
			if($lastname != $this->lastname)
			{
				$request->execute();
			}
			
			if(!password_verify($password, $pdo->query("SELECT password FROM utilisateurs WHERE id = ".$this->id)->fetch()[0]))
			{
				$request->execute();
			}
		}
		
		public function isConnected()
		{
			if(!is_null($this->id))
			{
				return true;
			}
			else
			{
				return false;
			}
		}
	
		public function getAllInfos()
		{
			return [$this->id, $this->login, $this->email, $this->firstname, $this->lastname];
		}
	
		public function getLogin()
		{
			if(!is_null($this->id))
			{
				return $this->login;
			}
		}
		
		public function getEmail()
		{
			if(!is_null($this->id))
			{
				return $this->email;
			}
		}
		
		public function getFirstname()
		{
			if(!is_null($this->id))
			{
				return $this->firstname;
			}
		}
		
		public function getLastname()
		{
			if(!is_null($this->id))
			{
				return $this->lastname;
			}
		}
		
		public function refresh()
		{
			if(!is_null($this->id))
			{
				$pdo = new PDO("mysql:host=localhost;dbname=poo","root","");
				$usr_data = $pdo->query("SELECT * FROM utilisateurs WHERE id = ".$this->id)->fetch(PDO::FETCH_ASSOC);
				if(!empty($usr_data))
				{
					$this->id = $usr_data["login"];
					$this->email = $usr_data["email"];
					$this->firstname = $usr_data["firstname"];
					$this->lastname = $usr_data["lastname"];
				}				
			}
		}
	}	

?>