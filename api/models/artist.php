<?php
    require_once(__DIR__ . '/common/dao.php');

    class ArtistDAO extends DAO {
        private static $instance = null;

        public function __construct() {
            $props = [
                'ArtistId' => 'int',
                'Name' => 'str',
            ];
            parent::__construct('artist', 'ArtistId', $props);
        }

        static public function getInstance() {
            if (is_null(self::$instance)) {
                self::$instance = new ArtistDAO();
            }

            return self::$instance;
        }
    }
?>