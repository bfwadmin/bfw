<?php
namespace Lib\Socket;

error_reporting(E_ALL);
set_time_limit(0);

class SocketServer
{

    private $server_host;
 // 服务器IP
    private $server_port;
 // 服务器端口
    private $client_host;
 // 客户端IP
    private $client_port;
 // 客户端端口
    private $create_socket = null;

    private $accept_socket = null;

    private $get_data = "";

    private $send_data = "";
    
    // 够造函数
    public function __construct($host, $port)
    {
        if (! extension_loaded("socket")) {
            exit("请先打开socket扩展！");
        }
        if (empty($host))
            exit("请输入目标主机IP！");
        if (empty($port))
            exit("请输入有效端口号！");
        $this->server_host = $host;
        $this->server_port = $port;
        $this->CreateSocket();
    }
    
    // 创建一个socket并将其用来绑定监听端口
    private function createSocket()
    {
        if (($this->create_socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) == false) {
            echo "socket_create() failed. reason:" . socket_strerror(socket_last_error()) . "\n";
        }
        if (socket_bind($this->create_socket, $this->server_host, $this->server_port) == false) {
            echo "socket_bind()failed. reason:" . socket_strerror(socket_last_error($this->create_socket)) . "\n";
        }
        if (socket_listen($this->create_socket, 5) == false) {
            echo "socket_listen()failed. reason:" . socket_strerror(socket_last_error($this->create_socket)) . "\n";
        }
    }
    
    // 向目标主机发起连接
    public function connectClient()
    {
        if (socket_getpeername($this->create_socket, $this->client_host, $this->client_port) == null) {
            echo "socket_getpeername()failed. reason:" . socket_strerror(socket_last_error($this->create_socket)) . "\n";
        }
        if (socket_connect($this->create_socket, $this->client_host, $this->client_port) == false) {
            echo "socket_connect()failed. reason:" . socket_strerror(socket_last_error($this->create_socket)) . "\n";
        }
    }
    // 接受连接获取到一个socket资源，想客户端读取以及传输信息
    public function wr()
    {
        do { // 循环防止阻塞延迟
            if (($this->accept_socket = socket_accept($this->create_socket)) == null) {
                echo "socket_accept()failed. reason:" . socket_strerror(socket_last_error($this->create_socket)) . "\n";
                break;
            }
            
            $this->get_data = socket_read($this->accept_socket, 8192);
            $this->send_data = $this->operateData($this->get_data);
            if (socket_write($this->accept_socket, $this->send_data, strlen($this->send_data)) == false) {
                echo "socket_write() failed reason:" . socket_strerror(socket_last_error($this->accept_socket)) . "\n";
            }
            socket_close($this->accept_socket);
        } while (true);
    }
    
    // 数据处理
    private function operateData()
    {
        return;
    }
    
    // 关闭监听socket
    private function closeSocket()
    {
        socket_close($this->createSocket);
    }
    
    // 析构函数
    public function __destruct()
    {
        $this->closeSocket();
    }
}

?>