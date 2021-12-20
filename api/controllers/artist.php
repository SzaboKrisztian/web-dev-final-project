<?php
    require_once(__DIR__ . "/../models/artist.php");
    require_once(__DIR__ . "/../utils.php");

    $artists = ArtistDAO::getInstance();

    class ArtistsController {
        static function getAll(
            string|null $orderby = null,
            bool|null $desc = null,
            int|null $limit = null,
            int|null $offset = null,
        ) {
            global $artists;

            validateTypes([
                'string|null' => $orderby,
                'bool|null' => $desc,
                'int|null' => $limit,
                'int|null' => $offset,
            ]);

            return $artists->findAll(
                orderby: $orderby,
                desc: $desc,
                limit: $limit,
                offset: $offset,
            );
        }

        static function getOne($id) {
            global $artists;

            $id = is_string($id) ? intval($id, 10) : $id;
            if (!is_int($id)) {
                throw new Exception("Invalid id");
            }

            return $artists->findByPk($id);
        }
    }
?>