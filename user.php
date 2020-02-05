<?php
/*
	user->register() = Get the user infos and send them to tye Database
	




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
				echo "Au revoir !";
			}
			else
			{
				echo "Vous n'etes pas connecté";
			}
		}

	}	
	
	$enzo = new user();
	// $enzo->register("0nz3", "0000", "enzo-mandine@laplateforme.io", "enzo", "mandine");
	$enzo->connect("0nz3", "0000");
	
?>