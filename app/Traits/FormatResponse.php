<?php


namespace App\Traits;


trait FormatResponse
{
    public function format($data, string $data_type = "string")
    {
        if (empty($data)) {
            switch ($data_type) {
                case "integer":
                    return 0;
                case "double":
                    return 0.0;
                case "array":
                    return [];
                case "boolean":
                    return false;
                case "object":
                    return new \stdClass();
                default:
                    return "";
            }
        } else {
            try {
                settype($data, $data_type);
            } catch (\Exception $exception) {
            } finally {
//                if ($data_type == "double") {
//                    return sprintf("%.1f", (double)$data);
//                }
                return $data;
            }
        }
    }

}
