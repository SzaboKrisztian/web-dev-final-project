<?php
    require_once(__DIR__ . '/common/dao.php');
    require_once(__DIR__ . '/invoice.php');
    require_once(__DIR__ . '/track.php');

    class InvoiceLineDAO extends DAO {
        private static $instance = null;

        private function __construct() {
            $props = [
                'InvoiceLineId' => 'int',
                'InvoiceId' => 'int',
                'TrackId' => 'int',
                'UnitPrice' => 'float',
                'Quantity' => 'int',
            ];
            $refs = [
                'InvoiceId' => InvoiceDAO::class,
                'TrackId' => TrackDAO::class,
            ];
            parent::__construct('invoiceline', 'InvoiceLineId', $props, $refs);
        }

        static public function getInstance() {
            if (is_null(self::$instance)) {
                self::$instance = new InvoiceLineDAO();
            }

            return self::$instance;
        }
    }
?>