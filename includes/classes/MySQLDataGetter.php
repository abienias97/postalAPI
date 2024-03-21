<?php
    
    require_once 'includes/interfaces/DataGetterInterface.php';

    class MySQLDataGetter implements DataGetterInterface
    {
        private $host;
        private $user;
        private $password;
        private $database;
        private $connection;
        
        public function __construct($host, $user, $password, $database) {
            $this->host = $host;
            $this->user = $user;
            $this->password = $password;
            $this->database = $database;
            $this->openConnection();
        }
        
        public function __destruct() {
            $this->closeConnection();
        }
        
        private function openConnection() {
            $this->connection = new mysqli($this->host, $this->user, $this->password, $this->database);
            $this->connection->set_charset('utf8');
        }
        
        private function closeConnection() {
            if (isset($this->connection)) {
                try {
                    $this->connection->close();
                } catch (Exception $ex) {
                    //go to void
                }
            }
        }
        
        private function sanitizeData($data) {
            $safeData = mysqli_real_escape_string($this->connection, $data);
            return $safeData;
        }
        
        private function getAllByField($field, $value, $table) {
            $safeField = $this->sanitizeData($field);
            $safeValue = $this->sanitizeData($value);
            $safeValue = $this->sanitizeData($table);
            $statement = $this->connection->prepare("SELECT * FROM ".$table." WHERE ".$field." = ?;");
            $statement->bind_param('s', $value);
            $statement->execute();        
            return $statement->get_result();
        }
        
        private function getRawDataByPostalCode($postalCode) {
            return $this->getAllByField('code', $postalCode, 'postal_codes');
        }
        
        public function getDataByPostalCode($postalCode) {
            $response = $this->getRawDataByPostalCode($postalCode)->fetch_assoc();
            
            if($response == NULL) {
                return null;
            }
            
            return $response;
        }
    }
