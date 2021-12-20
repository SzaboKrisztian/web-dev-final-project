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
    
    function validateTypes($rules) {
        foreach (array_keys($rules) as $varName) {
            global ${$varName};

            $types = explode('|', $rules[$varName]);
            $valid = [];
            foreach ($types as $type) {
                switch ($type) {
                    case 'null':
                        $valid[] = is_null(${$varName});
                        break;
                    case 'int':
                        $datapoint = is_string(${$varName}) ? intval(${$varName}, 10) : ${$varName};
                        $valid[] = is_int($datapoint);
                        break;
                    case 'str':
                    case 'string':
                        $valid[] = is_string(${$varName});
                        break;
                }
            }
            if (!in_array(true, $valid, true)) {
                Responde::badRequest();
            }
        }
    }
?>