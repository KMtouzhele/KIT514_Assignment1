<?php
include_once("model/ModelRegistration.php");

class User
{
    public $id;
    public $username;
    public $roleId;
    public $driverId;
    public $description;

    public function __construct(
        $id,
        $username,
        $roleId,
        $driverId,
        $description
    ) {
        $this->id = $id;
        $this->username = $username;
        $this->roleId = $roleId;
        $this->driverId = $driverId;
        $this->description = $description;
    }

    public function driverName()
    {
        $driverid = $this->driverId;
        $model = new ModelRegistration();
        $driverName = $model->getDriverName($driverid);
        return $driverName;
    }
}
