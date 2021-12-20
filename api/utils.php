<?php
    class Responde {
        static function notFound() {
            header("HTTP/1.1 404 Not Found");
            http_response_code(404);
            echo '{"message":"URI not found."}';
            ob_end_clean();
        }

        static function unauthorized() {
            header("HTTP/1.1 401 Unauthorized");
            http_response_code(401);
            echo '{"message":"You\'re not authorized to perform this action."}';
            ob_end_flush();
        }

        static function badRequest() {
            header("HTTP/1.1 400 Bad Request");
            http_response_code(400);
            echo '{"message":"Invalid request."}';
            ob_end_flush();
        }
    }

    function getVar($params, $path, $default) {
        $data = $params;
        for ($i = 0; $i < count($path); $i += 1) {
            if (!isset($data[$path[$i]])) {
                return $default;
            }
            $data = $data[$path[$i]];
        }
        return $data;
    }
    
    function validateTypes($rules) {
        foreach ($rules as $rule => $value) {
            $types = explode('|', $rule);
            $valid = [];
            foreach ($types as $type) {
                switch ($type) {
                    case 'null':
                        $valid[] = is_null($value);
                        break;
                    case 'int':
                        $datapoint = is_string($value) ? intval($value, 10) : $value;
                        $valid[] = is_int($datapoint);
                        break;
                    case 'str':
                    case 'string':
                        $valid[] = is_string($value);
                        break;
                    case 'bool':
                        $valid[] = is_bool($value);
                        break;
                }
                if (in_array(true, $valid, true)) {
                    break;
                }
            }
        }
        if (!in_array(true, $valid, true)) {
            Responde::badRequest();
        }
    }
?>