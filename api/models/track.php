<?php
    require_once(__DIR__ . '/common/dao.php');
    require_once(__DIR__ . '/album.php');
    require_once(__DIR__ . '/mediatype.php');
    require_once(__DIR__ . '/genre.php');

    class TrackDAO extends DAO {
        private static $instance = null;

        private function __construct() {
            $props = [
                'TrackId' => 'int',
                'Name' => 'str',
                'AlbumId' => 'int',
                'MediaTypeId' => 'int',
                'GenreId' => 'int',
                'Composer' => 'str',
                'Milliseconds' => 'int',
                'Bytes' => 'int',
                'UnitPrice' => 'float',
            ];
            $refs = [
                'AlbumId' => AlbumDAO::class,
                'MediaTypeId' => MediaTypeDAO::class,
                'GenreId' => GenreDAO::class,
            ];
            parent::__construct('track', 'TrackId', $props, $refs);
        }

        static public function getInstance() {
            if (is_null(self::$instance)) {
                self::$instance = new TrackDAO();
            }

            return self::$instance;
        }
    }
?>