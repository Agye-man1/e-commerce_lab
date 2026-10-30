<?php

require_once __DIR__ . '/../core/db_class.php';

class CustomerClass extends Database {

    // Check if an email already exists
    public function emailExists($email) {

        $sql = "SELECT customer_email FROM customer WHERE customer_email = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();

        $exists = $result->num_rows > 0;

        $stmt->close();

        return $exists;
    }


    // Register a new customer
    public function addCustomer($name, $email, $pass, $country, $city, $contact) {

        $hashedPassword = password_hash($pass, PASSWORD_BCRYPT);

        $sql = "INSERT INTO customer
                (customer_name, customer_email, customer_pass,
                 customer_country, customer_city, customer_contact)
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param(
            "ssssss",
            $name,
            $email,
            $hashedPassword,
            $country,
            $city,
            $contact
        );

        $success = $stmt->execute();

        $stmt->close();

        return $success;
    }
    
    public function getCustomerByEmail($email) {

        $sql = "SELECT * FROM customer WHERE customer_email = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();

        $customer = $result->fetch_assoc();

        $stmt->close();

        return $customer;
    }

}
    
    


?>
