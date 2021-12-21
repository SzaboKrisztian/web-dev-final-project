<?php
    class Responde {
        static function notFound() {
            header("HTTP/1.1 404 Not Found");
            http_response_code(404);
            echo '{"message":"URI not found."}';
            ob_end_clean();
            exit();
        }

        static function unauthorized() {
            header("HTTP/1.1 401 Unauthorized");
            http_response_code(401);
            echo '{"message":"You\'re not authorized to perform this action."}';
            ob_end_flush();
            exit();
        }

        static function badRequest($error = null) {
            header("HTTP/1.1 400 Bad Request");
            http_response_code(400);
            $res = [
                'message' => 'Invalid request.'
            ];
            if ($error) {
                $res['error'] = $error;
            }
            echo(json_encode($res));
            ob_end_flush();
            exit();
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

    function extractQueryParams($params) {
        $result = [
            'orderby' => getVar($params, ['query', 'orderby'], null),
            'desc' => getVar($params, ['query', 'desc'], null),
            'limit' => getVar($params, ['query', 'limit'], null),
            'offset' => getVar($params, ['query', 'offset'], null),
            'query' => getVar($params, ['query', 'query'], null),
        ];
        return $result;
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
                if (count($valid) > 0 && $valid[count($valid) - 1]) {
                    break;
                }
            }
            if (!in_array(true, $valid, true)) {
                Responde::badRequest();
            }
        }
    }
?>