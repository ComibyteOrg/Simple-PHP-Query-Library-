<?php 
    namespace Config\Conn;
    use mysqli;
    class Conn{
        private $db_host;
        private $db_user;
        private $db_pass;
        private $db_name;
        public $conn;

        public function __construct(){
            $this->db_host = "localhost";
            $this->db_user = "root";
            $this->db_pass = "";
            $this->db_name = "iceland";
            $this->conn = new mysqli($this->db_host, $this->db_user, $this->db_pass, $this->db_name, 4306);

            if($this->conn->connect_error){
                die("Connection Error ". $this->conn->connect_error);
            }
        }

        }
