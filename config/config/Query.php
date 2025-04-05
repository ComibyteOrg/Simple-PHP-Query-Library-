<?php 
    require_once "Conn.php";

    class Query extends Conn{
        // Select Method
        public function select($table, $selectors = "*", $conditions = "", $types = "", $params = array()){
            $columns = is_array($selectors) ? implode(', ', $selectors) : $selectors;

            $query = "SELECT $columns FROM $table";
            if (!empty($conditions)) {
                $query .= " WHERE $conditions";
            } 
            $stmt = $this->conn->prepare($query);
            if (!$stmt) {
                die("Query error: " . $this->conn->error);
            }

            if (!empty($params)) {
                if (!is_array($params)) {
                    throw new InvalidArgumentException('Params must be an array.');
                }
                $stmt->bind_param($types, ...$params);
            }
            $stmt->execute();
            $result = $stmt->get_result();

            return $result;
        } 



        // Update method
        function update($table, $data, $where) {
            $setParts = [];
            $types = '';
            $values = [];
 
            foreach ($data as $column => $value) {
                $setParts[] = "`$column` = ?";
                $types .= 's';  
                $values[] = $value;            
            }
        
            $setQuery = implode(', ', $setParts);
        
            $sql = "UPDATE `$table` SET $setQuery WHERE $where";

            if ($stmt = $this->conn->prepare($sql)) {
                $stmt->bind_param($types, ...$values);

                if ($stmt->execute()) {
                    $stmt->close();
                    return true;
                } else {
                    $stmt->close();
                    return false;
                }
            } else {
                return false;
            }
        }


        // Delete Method
        public function delete($table, $condition = "", $datatypes = "", $values = []){
            $query = "DELETE FROM $table WHERE $condition";
            $stmt = $this->conn->prepare($query);

            if($stmt->prepare($query) === false){
                die("<h1>Failed to Prepare (Update):</h1> ". $this->conn->error);
            }

            $stmt->bind_param($datatypes, ...$values);

            if(!$stmt->execute() || $stmt->errno){
                die("<h1>Could Not delete Data: </h1>". $this->conn->error);
            }

            return $stmt->execute();
        }

        

        // Insert Method
       public function insert($table, $datas){
            $columns = implode(', ', array_keys($datas));
            $placeholders = implode(',', array_fill(0, count($datas), "?"));

            $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
            $stmt = $this->conn->prepare($sql);

            if($stmt == false){
                die("<h1>Failed to prepare</h1>: ({$this->conn->error})");
            }

            $types = '';
            foreach ($datas as $data) {
                if (is_int($data)) {
                    $types .= 'i';
                } elseif (is_double($data) || is_float($data)) {
                    $types .= 'd';
                } else{
                    $types .= 's';
                }
            }
            // $type = str_repeat($types, count($datas));
            $stmt->bind_param($types, ...array_values($datas));

            if($stmt->execute()){
                return true;
            }else{
                die("<h1>Failed to Execute </h1>: {$stmt->error}");
            }
        }
    }
