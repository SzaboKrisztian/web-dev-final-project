<?php
    require_once(__DIR__ . "/../models/album.php");
    require_once(__DIR__ . "/../utils.php");

    $albums = AlbumDAO::getInstance();

    class AlbumController {
        static function getAll(
            string|null $orderby = null,
            bool|null $desc = null,
            int|null $limit = null,
            int|null $offset = null,
            string|null $query = null,
        ) {
            global $albums;

            validateTypes([
                'string|null' => $orderby,
                'bool|null' => $desc,
                'int|null' => $limit,
                'int|null' => $offset,
                'string|null' => $query,
            ]);

            $escaped = strtolower($albums->getPdo()->quote("%$query%"));
            $where = $query ? "`Name` like $escaped" : null;

            return $albums->findAll(
                orderby: $orderby,
                desc: $desc,
                limit: $limit,
                offset: $offset,
                where: $where,
            );
        }

        static function getOne($id) {
            global $albums;

            $id = is_string($id) ? intval($id, 10) : $id;
            if (!is_int($id)) {
                throw new Exception("Invalid id");
            }

            return $albums->findByPk($id);
        }

        static function create($data) {
            global $albums;

            return $albums->create($data);
        }

        static function update($id, $data) {
            global $albums;

            return $albums->update($id, $data);
        }

        static function delete($id) {
            global $albums;

            return $albums->delete($id);
        }
    }
?>