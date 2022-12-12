<?php

class response
{
    private $status;
    private $message;
    private $errors;

    public function __construct($status = null, $message = null, $errors = null){
        $this->status = $status;
        $this->message = $message;
        $this->errors = $errors;
    }

    public function send_response(): void
    {
        if (is_null($this->errors)){
            echo json_encode([
                'status' => $this->status,
                'message' => $this->message
            ]);
        }
        else {
            echo json_encode([
                'status' => $this->status,
                'message' => $this->message,
                'errors' => $this->errors
            ]);
        }
    }
}