<?php
/**
 * 写入日志文件
 */

class SaveLog
{
    public function __construct($res,$title)
    {
        $this->saveLog($res,$title);
    }

    public function saveLog($res,$title)
    {
        // 读写方式打开文件，将文件指针指向文件末尾。如果文件不存在，则创建。
        $myFile = fopen($title, "a+");
        fwrite($myFile,$res);
        // 关闭打开的文件
        fclose($myFile);
    }
}