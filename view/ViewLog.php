<?php
include_once('view/LogTable.php');
include_once('view/LogList.php');
include_once('view/LogJson.php');

class ViewLog
{
    private $logTable;
    private $logList;
    private $logJson;
    public function __construct()
    {
        $this->logTable = new LogTable();
        $this->logList = new LogList();
        $this->logJson = new LogJson();
    }
    public function output($logs)
    {
        $search = $_POST['search'] ?? '';
        ?>
        <html>

        <head>
            <title>Logs</title>
            <link rel="stylesheet" type="text/css" href="/css/style.css">
        </head>

        <body>

            <h1>Logs</h1>
            <form method="POST" action="index.php/?action=home">
                <input type="submit" value="Go Back to Home">
            </form>
            <form method="POST" action="">
                <p>Data Format</p>
                <input type="submit" name="view" value="Table">
                <input type="submit" name="view" value="List">
                <input type="submit" name="view" value="JSON">
            </form>
            <form method="POST" action="?action=accesslog">
                <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>"
                    placeholder="Search IP Address...">
                <input type="submit" value="Search">
            </form>
            <?php
            if (isset($_POST['view'])) {
                $_SESSION['view'] = $_POST['view'];
            } else {
                $_SESSION['view'] = 'Table';
            }
            switch ($_SESSION['view']) {
                case 'Table':
                    $this->logTable->output($logs);
                    break;
                case 'List':
                    $this->logList->output($logs);
                    break;
                case 'JSON':
                    $this->logJson->output($logs);
                    break;
                default:
                    $this->logTable->output($logs);
                    break;
            }

            ?>
        </body>

        </html>
        <?php
    }
}
?>