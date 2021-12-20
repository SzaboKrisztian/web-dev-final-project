<?php
    require_once(__DIR__ . '/common/dao.php');
    require_once(__DIR__ . '/artist.php');

    class AlbumDAO extends DAO {
        private static $instance = null;

        private function __construct() {
            $props = [
                'AlbumId' => 'int',
                'Title' => 'str',
                'ArtistId' => 'int',
            ];
            $refs = ['ArtistId' => ArtistDAO::class];
            parent::__construct('album', 'AlbumId', $props, $refs);
        }

        static public function getInstance() {
            if (is_null(self::$instance)) {
                self::$instance = new AlbumDAO();
            }

            return self::$instance;
        }
    }
?>