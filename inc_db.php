<?php
class dbconn{
		public $conn = NULL;
		public $host = 'localhost';
		public $user = 'root';
		public $pass = '';
		public $dbname = 'impression';

		function dbconn() {
			 register_shutdown_function(array(&$this,"close"));
		}

		public function connect($nhost, $nuser, $npass, $ndbname){
			$this->host = $nhost;
			$this->user = $nuser;
			$this->pass = $npass;
			$this->dbname = $ndbname;

			$this->conn = mysqli_connect($this->host, $this->user, $this->pass, $this->dbname);
			if (mysqli_connect_errno()){
				die('Could not connect to db :'. mysqli_connect_error());
			}
			$this->conn->set_charset("utf8");
		}

		public function execSQL($sql){
			if ($this->conn){
				$res = mysqli_query($this->conn, $sql);
				return $res;
			} else {
				die('Could not execute query: not connected to db.');
			}
		}

		public function close(){
			if ($this->conn){
				mysqli_close($this->conn);
			}
		}
}
?>
