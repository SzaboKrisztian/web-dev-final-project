<?php
    require_once(__DIR__ . '/common/dao.php');

    class MediaTypeDAO extends DAO {
        private static $instance = null;

        private function __construct() {
            $props = [
                'MediaTypeId' => 'int',
                'Name' => 'str',
            ];
            parent::__construct('mediatype', 'MediaTypeId', $props);
        }

        static public function getInstance() {
            if (is_null(self::$instance)) {
                self::$instance = new MediaTypeDAO();
            }

            return self::$instance;
        }
    }
?>