<?php

class Model_User extends RedBean_SimpleModel
{
    public function update()
    {
    }

    public function open()
    {
        if (isset($this->bean->id) && (int) $this->bean->id) {
            $this->bean->id = (int) $this->bean->id;
            $this->bean->active = (int) $this->bean->active;
        }
    }
}