<?php
    require_once(__DIR__ . "/../models/mediatype.php");
    require_once(__DIR__ . "/../utils.php");

    $mediatypes = MediaTypeDAO::getInstance();

    class MediaTypeController {
        static function getAll(
            string|null $orderby = null,
            bool|null $desc = null,
            int|null $limit = null,
            int|null $offset = null,
            string|null $query = null,
        ) {
            global $mediatypes;

            validateTypes([
                'string|null' => $orderby,
                'bool|null' => $desc,
                'int|null' => $limit,
                'int|null' => $offset,
                'string|null' => $query,
            ]);

            $escaped = strtolower($mediatypes->getPdo()->quote("%$query%"));
            $where = $query ? "`Name` like $escaped" : null;

            return $mediatypes->findAll(
                orderby: $orderby,
                desc: $desc,
                limit: $limit,
                offset: $offset,
                where: $where,
            );
        }

        static function getOne($id) {
            global $mediatypes;

            $id = is_string($id) ? intval($id, 10) : $id;
            if (!is_int($id)) {
                throw new Exception("Invalid id");
            }

            return $mediatypes->findByPk($id);
        }

        static function create($data) {
            global $mediatypes;

            return $mediatypes->create($data);
        }

        static function update($id, $data) {
            global $mediatypes;

            return $mediatypes->update($id, $data);
        }

        static function delete($id) {
            global $mediatypes;

            return $mediatypes->delete($id);
        }
    }
?>