<?php
namespace Lib;

use Lib\Util\HttpUtil;

class BoMoniter
{

    private $_pdo = null;

    const UNSERVICENUM = 3;

    private function initdb($_dbfilepath)
    {
        if ($this->_pdo == null) {
            $this->_pdo = new \PDO('sqlite:' . $_dbfilepath);
        }
        $this->_pdo->exec("CREATE TABLE IF NOT EXISTS service (
            id INTEGER PRIMARY KEY,
            dom TEXT,
            cont TEXT,
            act TEXT,
            providerip TEXT,
           serviceurl TEXT,
           regtime TEXT,
             lang TEXT,
             seckey TEXT,
          weight INTEGER,
          reportnum INTEGER)");
        $this->_pdo->exec("CREATE TABLE IF NOT EXISTS client (
            id INTEGER PRIMARY KEY,
            dom TEXT,
           customerip TEXT,
           notifyurl TEXT,
           gettime TEXT)");
    }
    // 注册到监督端
    public function work($_controler, $_action, $_domian)
    {
        $_dbfilepath = APP_ROOT . DS . 'Data' . DS . 'service.db';
        // 服务注册
        if ($_controler == "service" && $_action == "reg") {
            $this->initdb($_dbfilepath);
            $_args = unserialize($_POST['data']);
            $_notifyurl = trim(strtolower($_POST['notifyurl']));
            $_serviceurl = trim(strtolower($_POST['serviceurl']));
            $_lang = strtolower($_POST['lang']);
            $_ip = IP;
            $_weight = 100;
            $_reportnum = 0;
            if (! empty($_args)) {
                $time = date('Y-m-d H:i:s');
                foreach ($_args as $item) {
                    $sql = "SELECT id FROM service where dom=:dom and cont=:cont and act=:act and serviceurl=:serviceurl";
                    $stmt = $this->_pdo->prepare($sql);
                    $_data = [];
                    if ($stmt) {
                        $stmt->bindParam(':dom', $item[0]);
                        $stmt->bindParam(':cont', $item[1]);
                        $stmt->bindParam(':act', $item[2]);
                        $stmt->bindParam(':serviceurl', $_serviceurl);
                        $stmt->execute();
                        $_data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                        $stmt->closeCursor();
                    } else {
                        var_dump($this->_pdo->errorInfo());
                    }
                    
                    if (empty($_data)) {
                        $sql = "INSERT INTO service (seckey,lang,dom,serviceurl,cont,act,providerip,regtime,weight,reportnum) VALUES (:seckey,:lang,:dom,:serviceurl,:cont,:act,:providerip,:regtime,:weight,:reportnum)";
                        $stmt = $this->_pdo->prepare($sql);
                        if ($stmt) {
                            $stmt->bindParam(':seckey', $item[3]);
                            $stmt->bindParam(':lang', $_lang);
                            $stmt->bindParam(':dom', $item[0]);
                            $stmt->bindParam(':cont', $item[1]);
                            $stmt->bindParam(':act', $item[2]);
                            $stmt->bindParam(':providerip', $_ip);
                            $stmt->bindParam(':serviceurl', $_serviceurl);
                            $stmt->bindParam(':regtime', $time);
                            $stmt->bindParam(':weight', $_weight);
                            $stmt->bindParam(':reportnum', $_reportnum);
                            $stmt->execute();
                            $stmt->closeCursor();
                        } else {
                            var_dump($this->_pdo->errorInfo());
                        }
                    }
                }
            }
            echo "ok";
        }
        // 服务获取
        if ($_controler == "service" && $_action == "get") {
            $_ip = IP;
            $_date = date('Y-m-d H:i:s');
            $this->initdb($_dbfilepath);
            
            $sql = "SELECT dom,customerip,notifyurl FROM client where dom=:dom and customerip=:customerip and notifyurl=:notifyurl";
            $stmt = $this->_pdo->prepare($sql);
            $stmt->bindParam(':dom', $_domian);
            $stmt->bindParam(':notifyurl', $_GET['notifyurl']);
            $stmt->bindParam(':customerip', $_ip);
            $stmt->execute();
            $_clientedata = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            if (empty($_clientedata)) {
                $sql = "INSERT INTO client (dom,customerip,notifyurl,gettime) VALUES (:dom,:customerip,:notifyurl,:gettime)";
                $stmt = $this->_pdo->prepare($sql);
                $stmt->bindParam(':dom', $_domian);
                $stmt->bindParam(':notifyurl', $_GET['notifyurl']);
                $stmt->bindParam(':customerip', $_ip);
                $stmt->bindParam(':gettime', $_date);
                $stmt->execute();
            }
            $sql = "SELECT id,lang,seckey,cont,act,dom,serviceurl,weight FROM service where dom=:dom and cont=:cont and reportnum<=" . self::UNSERVICENUM;
            $stmt = $this->_pdo->prepare($sql);
            if ($stmt) {
                $stmt->bindParam(':cont', $_GET['sername']);
                $stmt->bindParam(':dom', $_domian);
                $stmt->execute();
                $_data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            } else {
                var_dump($this->_pdo->errorInfo());
            }
            echo json_encode($_data);
        }
        if ($_controler == "service" && $_action == "delclient") {
            $this->initdb($_dbfilepath);
            $_id = $_GET['id'];
            $sql = "DELETE FROM client WHERE id = :id";
            $stmt = $this->_pdo->prepare($sql);
            $stmt->execute([
                ':id' => $_id
            ]);
            echo "ok";
        }
        
        // 服务权值调整通知
        if ($_controler == "service" && $_action == "notify") {
            $this->initdb($_dbfilepath);
            $_id = $_GET['id'];
            $_weight = $_GET['weight'];
            $sql = "UPDATE service SET weight =:weight WHERE id = :id";
            $stmt = $this->_pdo->prepare($sql);
            $stmt->execute([
                ':weight' => $_weight,
                ':id' => $_id
            ]);
            $sql = "SELECT * FROM client where dom=:dom";
            $stmt = $this->_pdo->prepare($sql);
            $stmt->bindParam(':dom', $_GET['dom']);
            $stmt->execute();
            $_data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($_data as $item) {
                if ($item['notifyurl'] != "" && $item['notifyurl'] != "http://s.exm.com/") {
                    $_url = $item['notifyurl'] . "?notify=1&sername=" . $_GET['ser'] . "&domname=" . $_GET['dom'];
                    HttpUtil::HttpGet($_url);
                }
                // 执行通知
                // $item["notifyurl"];
            }
            echo "ok";
        }
        
        // 服务举报
        if ($_controler == "service" && $_action == "report") {
            $this->initdb($_dbfilepath);
            $_id = $_GET['serviceid'];
            $sql = "UPDATE service SET reportnum=reportnum+1  WHERE id = :id";
            $stmt = $this->_pdo->prepare($sql);
            $stmt->execute([
                ':id' => $_id
            ]);
            $sql = "SELECT cont,dom,reportnum FROM service where id=:id";
            $stmt = $this->_pdo->prepare($sql);
            $stmt->bindParam(':id', $_GET['serviceid']);
            $stmt->execute();
            $_servicedata = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            if (! empty($_servicedata)) {
                if ($_servicedata[0]["reportnum"] > self::UNSERVICENUM) {
                    $sql = "SELECT * FROM client where dom=:dom";
                    $stmt = $this->_pdo->prepare($sql);
                    $stmt->bindParam(':dom', $_servicedata[0]['dom']);
                    $stmt->execute();
                    $_data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                    foreach ($_data as $item) {
                        HttpUtil::HttpGet($item['notifyurl'] . "?notify=1&sername=" . $item['cont'] . "&domname=" . $item['dom']);
                        // 执行通知
                        // $item["notifyurl"];
                    }
                }
            }
            echo "ok";
        }
        
        // 服务检查
        if ($_controler == "service" && $_action == "check") {
            $this->initdb($_dbfilepath);
            $sql = "SELECT dom FROM service where  reportnum>" . UNSERVICENUM;
            $stmt = $this->_pdo->prepare($sql);
            $stmt->bindParam(':dom', $_GET['dom']);
            $stmt->execute();
            $_data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($_data as $item) {
                // 执行检查
                // $item["notifyurl"];
            }
            echo "ok";
        }
        // 服务web查看
        if ($_controler == "service" && $_action == "index") {
            if (IS_AJAX_REQUEST) {
                if ($_GET['username'] == SERVICE_M_USER && $_GET['password'] == SERVICE_M_PWD) {
                    Core::Cache("bfwserviceauth" . SESS_ID, "ok", 1800);
                    die("ok");
                } else {
                    die("账号错误");
                }
            }
            
            if (Core::Cache("bfwserviceauth" . SESS_ID) != "ok") {
                Core::V("login", "System", "v1", [
                    'refer' => URL
                ]);
                exit();
            }
            
            $_ser_val = isset($_GET['ser_inp']) ? $_GET['ser_inp'] : "";
            $_dom_val = isset($_GET['dom_inp']) ? $_GET['dom_inp'] : "";
            $_url_val = isset($_GET['url_inp']) ? $_GET['url_inp'] : "";
            $sql = "SELECT * FROM service ";
            $_wherestr = "";
            if ($_ser_val != "") {
                $_wherestr .= " cont=:cont and";
            }
            if ($_dom_val != "") {
                $_wherestr .= " dom=:dom and";
            }
            if ($_url_val != "") {
                $_wherestr .= " serviceurl=:serviceurl and";
            }
            $this->initdb($_dbfilepath);
            if ($_wherestr == "") {
                $sql .= " order by dom desc";
            } else {
                $sql .= " where ".rtrim($_wherestr, "and") . " order by dom desc";
            }
            $stmt = $this->_pdo->prepare($sql);
            if ($_ser_val != "") {
                $stmt->bindParam(':cont', $_ser_val);
            }
            if ($_dom_val != "") {
                $stmt->bindParam(':dom', $_dom_val);
            }
            if ($_url_val != "") {
                $stmt->bindParam(':serviceurl', $_url_val);
            }
            $stmt->execute();
            $_data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            
            // $_data = $this->_pdo->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
            $sql = "SELECT * FROM client order by dom desc";
            $_cdata = $this->_pdo->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
            // var_dump($_data);
            Core::V("service", "System", "v1", [
                'service_array' => $_data,
                'client_array' => $_cdata
            ]);
        }
    }
}
?>