<?php
    require_once(__DIR__ . '/common/dao.php');

    class CustomerDAO extends DAO {
        private static $instance = null;

        public function __construct() {
            $props = [
                'CustomerId' => 'int',
                'FirstName' => 'str',
                'LastName' => 'str',
                'Password' => 'str',
                'Email' => 'str',
                'Company' => 'str',
                'Address' => 'str',
                'City' => 'str',
                'State' => 'str',
                'Country' => 'str',
                'PostalCode' => 'str',
                'Phone' => 'str',
                'Fax' => 'str',
            ];
            parent::__construct('customer', 'CustomerId', $props);
        }

        static public function getInstance() {
            if (is_null(self::$instance)) {
                self::$instance = new CustomerDAO();
            }

            return self::$instance;
        }

        function findByEmail($email) {
            $sanitized = $this->pdo->quote($email);
            $items = $this->findAll(where: "Email like $sanitized");
            return count($items) == 1 ? $items[0] : null;
        }
    }
?>