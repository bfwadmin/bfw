<?php
namespace Lib;

/**
 * @author wangbo
 * 套接字类
 */
class BoSocket
{
    public function go(){

    }

    public static function Start($_ip = "0.0.0.0", $_port = 10000)
    {
        // Set time limit to indefinite execution
        set_time_limit(0);
        // Set the ip and port we will listen on
        // Create a TCP Stream socket
        $sock = socket_create(AF_INET, SOCK_STREAM, 0);

        // Bind the socket to an address/port
        socket_bind($sock, $_ip, $_port) or die('Could not bind to address');

        // Start listening for connections
        socket_listen($sock);

        // Non block socket type
        socket_set_nonblock($sock);

        // Loop continuously
        while (true) {
            unset($read);
            $j = 0;

            if (count($client)) {
                foreach ($client as $k => $v) {
                    $read[$j] = $v;

                    $j ++;
                }
            }

            $client = $read;

            if ($newsock = @socket_accept($sock)) {
                if (is_resource($newsock)) {
                    socket_write($newsock, "$j>", 2) . chr(0);
                    echo "New client connected $j";
                    $client[$j] = $newsock;
                    $j ++;
                }
            }

            if (count($client)) {
                foreach ($client as $k => $v) {
                    if (@socket_recv($v, $string, 1024, MSG_DONTWAIT) === 0) {
                        unset($client[$k]);
                        socket_close($v);
                    } else {
                        if ($string) {
                            echo "$k: $string\n";
                        }
                    }
                }
            }
            sleep(1);
        }
        // Close the master sockets
        socket_close($sock);
    }
}

?>