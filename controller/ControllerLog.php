<?php
include_once('view/ViewLog.php');
include_once('model/ModelLog.php');
class ControllerLog
{
    private $viewLog;
    private $modelLog;

    public function __construct()
    {
        $this->viewLog = new ViewLog();
        $this->modelLog = new ModelLog();
    }

    public function showLogs($search = null)
    {
        if ($search === null) {
            $logs = $this->modelLog->getLogs();
        } else {
            $logs = $this->modelLog->getSearchedLogs($search);
        }
        $this->viewLog->output($logs);
    }

    public function recordLog($log)
    {
        $this->modelLog->addNewLog($log);
    }

}