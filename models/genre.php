<?php
    require_once(__DIR__ . '/common/dao.php');

    class GenreDAO extends DAO {
        private static $instance = null;

        private function __construct() {
            $props = [
                'GenreId' => 'int',
                'Name' => 'str',
            ];
            parent::__construct('genre', 'GenreId', $props);
        }

        static public function getInstance() {
            if (is_null(self::$instance)) {
                self::$instance = new GenreDAO();
            }

            return self::$instance;
        }
    }
?>