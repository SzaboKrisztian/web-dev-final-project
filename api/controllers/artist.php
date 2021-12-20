<?php
    require_once(__DIR__ . "/../models/artist.php");
    require_once(__DIR__ . "/../utils.php");

    $artists = ArtistDAO::getInstance();

    class AristsController {
        static function getAll(
            string|null $orderby = null,
            bool|null $desc = false,
            int|null $limit = 0,
            int|null $offset = 0,
        ) {
            global $artists;

            validateTypes([
                'orderby' => 'string|null',
                'desc' => 'bool|null',
                'limit' => 'int|null',
                'offset' => 'int|null',
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