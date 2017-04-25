<?php

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/4/21
 * Time: 14:37
 */
class DB
{
    private $link = null;
    protected $host;
    protected $port;
    protected $user;
    protected $password;
    protected $charset;
    protected $db_name;

    private static $instance = null;

    /**
     * 禁止该类的实例对象进行克隆复制对象
     */
    private function __clone()
    {
    }

    /**
     * 对外提供一个创建该类实例的方法
     * @param $config
     * @return null
     */
    public static function getInstance($config = null)
    {
        if (!(static::$instance instanceof self)) {
            static::$instance = new self($config);
        }
        return static::$instance;
    }

    /**
     * 实现单例的基础：私有化该类的构造方法
     * DB constructor.
     * @param $config
     */
    private function __construct($config)
    {
        $this->host = isset($config['host']) ? $config['host'] : 'localhost';
        $this->port = isset($config['port']) ? $config['port'] : '3306';
        $this->user = isset($config['user']) ? $config['user'] : 'root';
        $this->password = isset($config['password']) ? $config['password'] : '';
        $this->charset = isset($config['charset']) ? $config['charset'] : 'utf8';
        $this->db_name = isset($config['db_name']) ? $config['db_name'] : 'blog';

        $this->link = mysqli_connect(
            "{$this->host}:{$this->port}", "{$this->user}", "$this->password")
        or die('数据库连接失败!');

        $this->selectDB($this->db_name);
        $this->setCharset($this->charset);
    }

    /**
     * 设置连接环境字符编码
     * @param $charset
     */
    public function setCharset($charset)
    {
        $sql = "set names {$charset}";
        $this->query($sql);
    }

    /**
     * 选择要操作的数据库
     * @param $db_name
     */
    public function selectDB($db_name)
    {
        $sql = "use {$db_name}";
        $this->query($sql);
    }

    /**
     * 关闭数据库连接
     */
    public function closeDB()
    {
        if (isset($this->link)) {
            mysqli_close($this->link);
        }
    }

    /**
     * 增删改
     * @param $sql
     * @return bool
     */
    public function execute($sql)
    {
        $this->query($sql);
        return true;
    }

    /**
     * 增
     * @param $sql
     * @return int|string
     */
    public function insert($sql)
    {
        $result = mysqli_query($this->link, $sql);
        if ($result === false) {
            echo "代码执行错误！请参考如下提示：";
            echo "<br />错误代号：" . mysqli_errno($this->link);
            echo "<br />错误内容：" . mysqli_error($this->link);
            echo "<br />错误代码：" . $sql;
            die();
        }
        return mysqli_insert_id($this->link);
    }

    /**
     * 返回结果是一个标量值
     * @param $sql
     * @return mixed
     */
    public function getData($sql)
    {
        $result = $this->query($sql);
        $num = mysqli_fetch_array($result);
        mysqli_free_result($result);
        return $num[0];
    }

    /**
     * 返回结果是一个一维数组
     * @param $sql
     * @return array
     */
    public function getRow($sql)
    {
        $result = $this->query($sql);
        $row = mysqli_fetch_array($result);
        mysqli_free_result($result);
        return $row;
    }

    /**
     * 返回结果是一个二维数组
     * @param $sql
     * @return array
     */
    public function getRows($sql)
    {
        $result = $this->query($sql);
        $arr = array();
        while ($row = mysqli_fetch_array($result)) {
            $arr[] = $row;
        }

        mysqli_free_result($result);
        return $arr;
    }

    /**
     * 错误处理并返回一个结果集
     * @param $sql
     * @return resource
     */
    private function query($sql)
    {
        $result = mysqli_query($this->link, $sql);
        if ($result === false) {
            echo "代码执行错误！请参考如下提示：";
            echo "<br />错误代号：" . mysqli_errno($this->link);
            echo "<br />错误内容：" . mysqli_error($this->link);
            echo "<br />错误代码：" . $sql;
            die();
        }
        return $result;
    }
}