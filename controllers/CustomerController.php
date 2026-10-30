<?php

require_once __DIR__ . '/../classes/CustomerClass.php';

class CustomerController {

    private $customerModel;

    public function __construct() {
        $this->customerModel = new CustomerClass();
    }

    // Register a new customer
    public function register($data) {

        $name = trim(strip_tags($data['name'] ?? ''));
        $email = trim($data['email'] ?? '');
        $password = $data['password'] ?? '';
        $country = trim(strip_tags($data['country'] ?? ''));
        $city = trim(strip_tags($data['city'] ?? ''));
        $contact = trim(strip_tags($data['contact'] ?? ''));

        // Check for empty fields
        if (
            $name === '' || $email === '' || $password === '' ||
            $country === '' || $city === '' || $contact === ''
        ) {
            return [
                'success' => false,
                'error' => 'Please fill in all fields.'
            ];
        }

        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return [
                'success' => false,
                'error' => 'Please enter a valid email address.'
            ];
        }

        // Check field lengths
        if (
            strlen($name) > 100 ||
            strlen($email) > 50 ||
            strlen($country) > 30 ||
            strlen($city) > 30 ||
            strlen($contact) > 15
        ) {
            return [
                'success' => false,
                'error' => 'One or more fields exceed the allowed length.'
            ];
        }

        // Check password length
        if (strlen($password) < 8) {
            return [
                'success' => false,
                'error' => 'Password must be at least 8 characters.'
            ];
        }

        // Check if email already exists
        if ($this->customerModel->emailExists($email)) {
            return [
                'success' => false,
                'error' => 'This email is already registered.'
            ];
        }

        // Save customer
        $success = $this->customerModel->addCustomer(
            $name,
            $email,
            $password,
            $country,
            $city,
            $contact
        );

        if ($success) {
            return [
                'success' => true,
                'message' => 'Registration successful!'
            ];
        }

        return [
            'success' => false,
            'error' => 'Registration failed. Please try again.'
        ];
    }
    // Log in an existing customer
public function login($data) {

$email = trim($data['email'] ?? '');
$password = $data['password'] ?? '';

if ($email === '' || $password === '') {
    return [
        'success' => false,
        'error' => 'Please enter your email and password.'
    ];
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return [
        'success' => false,
        'error' => 'Please enter a valid email address.'
    ];
}

$customer = $this->customerModel->getCustomerByEmail($email);

if (!$customer || !password_verify($password, $customer['customer_pass'])) {
    return [
        'success' => false,
        'error' => 'Incorrect email or password.'
    ];
}

session_regenerate_id(true);

$_SESSION['customer_id'] = $customer['customer_id'];
$_SESSION['customer_name'] = $customer['customer_name'];
$_SESSION['user_role'] = $customer['user_role'];

return [
    'success' => true,
    'message' => 'Login successful!'
];
}
}

?>
