<?php
    require_once(__DIR__ . "/../models/track.php");
    require_once(__DIR__ . "/../utils.php");

    $tracks = TrackDAO::getInstance();

    class TrackController {
        static function getAll(
            string|null $orderby = null,
            bool|null $desc = null,
            int|null $limit = null,
            int|null $offset = null,
            string|null $query = null,
        ) {
            global $tracks;

            validateTypes([
                'string|null' => $orderby,
                'bool|null' => $desc,
                'int|null' => $limit,
                'int|null' => $offset,
                'string|null' => $query,
            ]);

            $escaped = strtolower($tracks->getPdo()->quote("%$query%"));
            $where = $query ? "`Name` like $escaped" : null;

            return $tracks->findAll(
                orderby: $orderby,
                desc: $desc,
                limit: $limit,
                offset: $offset,
                where: $where,
            );
        }

        static function getOne($id) {
            global $tracks;

            $id = is_string($id) ? intval($id, 10) : $id;
            if (!is_int($id)) {
                throw new Exception("Invalid id");
            }

            return $tracks->findByPk($id);
        }

        static function create($data) {
            global $tracks;

            return $tracks->create($data);
        }

        static function update($id, $data) {
            global $tracks;

            return $tracks->update($id, $data);
        }

        static function delete($id) {
            global $tracks;

            return $tracks->delete($id);
        }
    }
?>