<?php

class FindCircles {

    private $env;
    private $whatImage;
    private $imagePath;
    private $minRadius;
    private $maxRadius;
    private $sensitivity;
    private $base64String;

    public function __construct($env, $whatImage, $minRadius, $maxRadius,
            $sensitivity, $base64String = null) {

        $this->env = $this->notSetOrEmpty($env) ? PROD_ENVIROMENT :
            ($env === DEV_ENVIROMENT ? DEV_ENVIROMENT : PROD_ENVIROMENT);

        if ($this->notSetOrEmpty($whatImage)) {
            if ($this->env === DEV_ENVIROMENT) {
                $this->whatImage = LAST_IMAGE;
                $this->imagePath = LAST_IMAGE_PATH;
            } else {
                $this->whatImage = NEW_IMAGE;
                $this->imagePath = NEW_IMAGE_PATH;
            }
        } elseif ($whatImage === DEFAULT_IMAGE) {
            $this->whatImage = DEFAULT_IMAGE;
            $this->imagePath = DEFAULT_IMAGE_PATH;
        } elseif ($whatImage === LAST_IMAGE) {
            $this->whatImage = LAST_IMAGE;
            $this->imagePath = LAST_IMAGE_PATH;
        } elseif ($this->env === DEV_ENVIROMENT) {
            $this->whatImage = LAST_IMAGE;
            $this->imagePath = LAST_IMAGE_PATH;
        } else {
            $this->whatImage = NEW_IMAGE;
            $this->imagePath = NEW_IMAGE_PATH;
        }

        $this->minRadius = $this->notSetOrEmpty($minRadius) ?
            DEFAULT_MIN_RADIUS : $minRadius;
        if (is_numeric($this->minRadius) &&
                !$this->isDecimal($this->minRadius)) {
            if ($this->minRadius <= 0) {
                throw new Exception(INVALID_MIN_RADIUS);
            }
        } else {
            throw new Exception(MIN_RADIUS_NOT_INT);
        }

        $this->maxRadius = $this->notSetOrEmpty($maxRadius) ?
            DEFAULT_MAX_RADIUS : $maxRadius;
        if (is_numeric($this->maxRadius) &&
                !$this->isDecimal($this->maxRadius)) {
            if ($this->maxRadius <= $this->minRadius) {
                throw new Exception(INVALID_MAX_RADIUS);
            }
        } else {
            throw new Exception(MAX_RADIUS_NOT_INT);
        }

        $this->sensitivity = $this->notSetOrEmpty($sensitivity) ?
            DEFAULT_SENSITIVITY : $sensitivity;
        if (is_numeric($this->sensitivity) ||
                $this->isDecimal($this->sensitivity)) {
            if ($this->sensitivity < 0.85 || $this->sensitivity > 1) {
                throw new Exception(INVALID_SENSITIVITY);
            }
        } else {
            throw new Exception(SENSITIVITY_NOT_INT);
        }

        if ($this->notSetOrEmpty($base64String)) {
            if (!$this->devEnviroment() && $this->processNewImage()) {
                throw new Exception(BASE64_NOT_FOUND);
            }
        } else {
            $this->base64String = $base64String;
        }

        set_time_limit(0);
    }

    public function execute() {
        if ($this->devEnviroment()) {
            $startTime = strtotime(date("Y-m-d H:i:s"));
        }

        if ($this->processNewImage() &&
                !$this->notSetOrEmpty($this->base64String)) {
            $this->imageFromBase64($this->base64String, NEW_IMAGE_PATH);
        } elseif ($this->processNewImage() &&
                $this->notSetOrEmpty($this->base64String)) {
            throw new Exception(BASE64_NOT_FOUND);
        }

        if (file_exists(SCRIPT_OUTPUT)) {
            while (!unlink(SCRIPT_OUTPUT));
        }
        if (file_exists(FINISHED_PROCESS)) {
            while (!unlink(FINISHED_PROCESS));
        }

//        print COMMAND . "('" . $this->env . "'," . $this->minRadius . "," .
//            $this->maxRadius . "," . $this->sensitivity . ");";exit;
        passthru(COMMAND . "('" .
            $this->env . "','" .
            $this->whatImage . "'," .
            $this->minRadius . "," .
            $this->maxRadius . "," .
            $this->sensitivity . ");");

        $start = time();
        while (!file_exists(FINISHED_PROCESS)) {
            // Check Timeout
            if ((time() - $start) > TIMEOUT) {
                throw new Exception(TIMEOUT_REACHED);
            }
        }

        if (file_exists(SCRIPT_OUTPUT)) {
            if (!($resultFile = file_get_contents(SCRIPT_OUTPUT))) {
                throw new Exception(FILE_NOT_READY);
            }
        } else {
            throw new Exception(RESULT_FILE_DOESNT_EXIST);
        }

        $res = '';
        if ($this->devEnviroment()) {
            $endTime = strtotime(date("Y-m-d H:i:s"));
            $totalProcessTime = $endTime - $startTime;

            $res = '<strong>Total process time: </strong>' . $totalProcessTime .
                ' seconds<br><br>' .
                '<strong>Parameters:</strong><br>' .
                'Image: ' . $this->whatImage . ' image<br>' .
                'Min radius: ' . $this->minRadius . '<br>' .
                'Max radius: ' . $this->maxRadius . '<br>' .
                'Sensitivity: ' . $this->sensitivity . '<br>';

            if ($this->whatImage === 'default') {
                $res .= '<br><strong>Source image:</strong><br>' .
                    '<img src="' . SHOW_DEFAULT_IMAGE . '"><br><br>' .
                    '<strong>Result image:</strong><br>' .
                    '<img src="' . SHOW_DEFAULT_IMAGE_RESULT . '"><br>';
            } else {
                $res .= '<br><strong>Source image:</strong><br>' .
                    '<img src="' . SHOW_LAST_IMAGE . '"><br><br>' .
                    '<strong>Result image:</strong><br>' .
                    '<img src="' . SHOW_LAST_IMAGE_RESULT . '"><br>';
            }

            $res .= '<br><strong>JSON result:</strong><br>';
        }

        return $res . $resultFile;
    }

    private function devEnviroment() {
        return $this->env === DEV_ENVIROMENT;
    }

    private function processNewImage() {
        return $this->whatImage === NEW_IMAGE;
    }

    private function processDefaultImage() {
        return $this->whatImage === DEFAULT_IMAGE;
    }

    private function processlastImage() {
        return $this->whatImage === LAST_IMAGE;
    }

    private function imageFromBase64($base64String, $outputFilePath) {
        if (($fp = fopen($outputFilePath, "wb")) === false) {
            throw new Exception(CANT_OPEN_IMAGE_FILE);
        }
        if (fwrite($fp, base64_decode($base64String)) === false) {
            throw new Exception(CANT_WRITE_TO_IMAGE_FILE);
        }
        if (fclose($fp) === false) {
            throw new Exception(CANT_CLOSE_IMAGE_FILE);
        }
    }

    private function notSetOrEmpty($var) {
         return !(isset($var) && ($var !== ''));
    }

    private  function isDecimal($var) {
        return is_numeric($var) && floor($var) != $var;
    }
} 