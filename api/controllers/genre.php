<?php
    require_once(__DIR__ . "/../models/genre.php");
    require_once(__DIR__ . "/../utils.php");

    $genres = GenreDAO::getInstance();

    class GenreController {
        static function getAll(
            string|null $orderby = null,
            bool|null $desc = null,
            int|null $limit = null,
            int|null $offset = null,
            string|null $query = null,
        ) {
            global $genres;

            validateTypes([
                'string|null' => $orderby,
                'bool|null' => $desc,
                'int|null' => $limit,
                'int|null' => $offset,
                'string|null' => $query,
            ]);

            $escaped = strtolower($genres->getPdo()->quote("%$query%"));
            $where = $query ? "`Name` like $escaped" : null;

            return $genres->findAll(
                orderby: $orderby,
                desc: $desc,
                limit: $limit,
                offset: $offset,
                where: $where,
            );
        }

        static function getOne($id) {
            global $genres;

            $id = is_string($id) ? intval($id, 10) : $id;
            if (!is_int($id)) {
                throw new Exception("Invalid id");
            }

            return $genres->findByPk($id);
        }

        static function create($data) {
            global $genres;

            return $genres->create($data);
        }

        static function update($id, $data) {
            global $genres;

            return $genres->update($id, $data);
        }

        static function delete($id) {
            global $genres;

            return $genres->delete($id);
        }
    }
?>