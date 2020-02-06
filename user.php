<?php
/*
	user::register() 	= Get the user infos and send them to tye Database
	user::connect() 	= Save the user datas in the user object
	user::disconnect() 	= Free user attributes 
	user::delete()		= Free user attributes and delete users data from database
	user::update()		= Change user data if entered other data than the one saved
	user::isConnected() = Return true if the user id is not null, false if it is
	user::getAllInfos() = Return all the stored data of the object
	user::getLogin|Email|Firstname|Lastname() = Return the current object's attribute
	user::refresh()		= Update object's attributes with database data
*/



	class user
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
				$conn = mysqli_connect("localhost","root","","poo");
				if(empty(mysqli_fetch_row(mysqli_query($conn, "SELECT login FROM utilisateurs WHERE login = '".$login."'"))))
				{
					if(mysqli_query($conn, "INSERT INTO `utilisateurs` (`id`, `login`, `email`, `firstname`, `lastname`, `password`)
											VALUES (NULL, '".$login."', '".$email."', '".$firstname."', '".$lastname."'
											, '".password_hash($password, PASSWORD_BCRYPT)."')"))
					{
						echo "Inscription validée<br>";
						return [$login, $password, $email, $firstname,$lastname] ;	
					}
					else
					{
						echo "Erreur des champs sont mal remplis <br>";
					}
				}
				else
				{
					echo "Login deja pris <br>";
				}
				mysqli_close($conn);
			}
		}
		
		public function connect($login, $password)
		{
			if(!empty($login) && !empty($password))
			{
				$conn = mysqli_connect("localhost","root","","poo");
				$usr_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM utilisateurs WHERE login = '".$login."'"));
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
			$conn = mysqli_connect("localhost","root","","poo");
			if(mysqli_query($conn, "DELETE FROM `utilisateurs` WHERE `utilisateurs`.`id` = ".$this->id))
			{
				$this->disconnect();
				echo "Compte supprimé<br>";
			}
		}
		
		public function update($login, $password , $email , $firstname, $lastname)
		{
			$conn = mysqli_connect("localhost","root","","poo");
			
			if(!is_null($login))
			{	
				if(empty(mysqli_fetch_row(mysqli_query($conn, "SELECT login FROM utilisateurs WHERE login = '".$login."'"))))
				{
					if($login != $this->login)
					{
						if(mysqli_query($conn, "UPDATE utilisateurs SET login = '".$login."' WHERE id=".$this->id))
						{
							echo "Login modifié (ancient: ".$this->login." - nouveau:".$login.")<br>";
						}
					}					
				}
			}
			
			if(!is_null($password))
			{	
				$usr_password = mysqli_fetch_row(mysqli_query($conn, "SELECT password FROM utilisateurs WHERE id =".$this->id))[0];
				if(!password_verify($password, $usr_password))
				{
					if(mysqli_query($conn, "UPDATE utilisateurs SET password = '".$password."' WHERE id=".$this->id))
					{
						echo "Password modifié <br>";
					}
				}
			}			
			
			if(!is_null($email))
			{			
				if($email != $this->email)
				{
					if(mysqli_query($conn, "UPDATE utilisateurs SET email= '".$email."' WHERE id=".$this->id))
					{
						echo "Email modifié (ancient: ".$this->email." - nouveau:".$email.")<br>";
					}
				}
			}			
			
			if(!is_null($firstname))
			{			
				if($firstname!= $this->firstname)
				{
					if(mysqli_query($conn, "UPDATE utilisateurs SET firstname = '".$firstname."' WHERE id=".$this->id))
					{
						echo "Firstname modifié (ancient: ".$this->firstname." - nouveau:".$firstname.")<br>";
					}
				}
			}
			
			if(!is_null($lastname))
			{			
				if($lastname!= $this->lastname)
				{
					if(mysqli_query($conn, "UPDATE utilisateurs SET lastname = '".$lastname."' WHERE id=".$this->id))
					{
						echo "Lastname modifié (ancient: ".$this->lastname." - nouveau:".$lastname.")<br>";
					}
				}
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
				$conn = mysqli_connect("localhost","root","","poo");
				$usr_data = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM utilisateurs WHERE id = ".$this->id));
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