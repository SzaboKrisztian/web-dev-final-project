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
            string|null $query = null,
        ) {
            global $artists;

            validateTypes([
                'string|null' => $orderby,
                'bool|null' => $desc,
                'int|null' => $limit,
                'int|null' => $offset,
                'string|null' => $query,
            ]);

            $escaped = strtolower($artists->getPdo()->quote("%$query%"));
            $where = $query ? "`Name` like $escaped" : null;

            return $artists->findAll(
                orderby: $orderby,
                desc: $desc,
                limit: $limit,
                offset: $offset,
                where: $where,
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

        static function create($data) {
            global $artists;

            return $artists->create($data);
        }

        static function update($id, $data) {
            global $artists;

            return $artists->update($id, $data);
        }

        static function delete($id) {
            global $artists;

            return $artists->delete($id);
        }
    }
?>