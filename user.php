<?php
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
				$this->login = $login;
				$this->password = $password;
				$this->email = $email;
				$this->firstname = $firstname;
				$this->lastname = $lastname;
				
				return( [$this->login, $this->password, $this->email, $this->firstname,$this->lastname] );
			}
		}
	}	
	
	$enzo = new user();
	var_dump($enzo->register("0nz3", "0000", "enzo-mandine@laplateforme.io", "enzo", "mandine"));
	
	
?>