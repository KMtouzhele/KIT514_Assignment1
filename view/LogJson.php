<?php
class LogJson
{
    public function output($logs)
    {
        $json = json_encode($logs);
        ?>
        <div class="container">
            <ul>
                <?php
                echo $json;
                ?>
            </ul>
        </div>
        <?php
    }
}
?>