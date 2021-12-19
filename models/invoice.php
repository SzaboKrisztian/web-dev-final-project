<?php
    require_once(__DIR__ . '/common/dao.php');
    require_once(__DIR__ . '/customer.php');

    class InvoiceDAO extends DAO {
        private static $instance = null;

        private function __construct() {
            $props = [
                'InvoiceId' => 'int',
                'CustomerId' => 'int',
                'InvoiceDate' => 'datetime',
                'BillingAddress' => 'str',
                'BillingCity' => 'str',
                'BillingState' => 'str',
                'BillingCountry' => 'str',
                'BillingPostalCode' => 'str',
                'Total' => 'float',
            ];
            $refs = ['CustomerId' => function ($id) { return CustomerDAO::getInstance()->findByPk($id); }];
            parent::__construct('invoice', 'InvoiceId', $props, $refs);
        }

        static public function getInstance() {
            if (is_null(self::$instance)) {
                self::$instance = new InvoiceDAO();
            }

            return self::$instance;
        }
    }
?>