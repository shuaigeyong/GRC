<?php 
require_once 'pdo_db.php';

class Customer {    
    private $stmt;

    public function addCustomer($data){
        global $dbh;
        // Prepare Query
        $this->stmt = $dbh->prepare('INSERT INTO customer (user_id, cust_name, cust_phone) VALUES (:user_id, :cust_name, :cust_phone)');
        
        // Bind Values
        $this->stmt->bindParam(':user_id', $data['user_id'], PDO::PARAM_INT);
        $this->stmt->bindParam(':cust_name', $data['cust_name'], PDO::PARAM_STR);
        $this->stmt->bindParam(':cust_phone', $data['cust_phone'], PDO::PARAM_STR);
        
        // Execute
        $this->stmt->execute();
    }
}

?> 