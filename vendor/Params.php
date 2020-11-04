<?php

class Params extends \ObjectAccess
{

    public function load($data)
    {
        if (empty($data)) {
            return;
        }
        foreach ($data as $key => $v) {
            $this->$key = $v;
        }
    }
}