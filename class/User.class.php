<?php
class User
{
    public $userid;
    public $username;
    private $password;
    private $email;
    public $title;
    public $content;
    public $db_con;
    public $noteid;
    public $share;
    public $mctime;
    public $cgid;


    // 方法：注册
    public function Register($password)
    {
        $this->password = mysqli_real_escape_string($this->db_con, md5($password));
        $reg_date = date('Y-m-d');
        // 毫秒时间轴
        list($msec, $sec) = explode(' ', microtime());
        $this->mctime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
        $sql = "INSERT INTO `users` (`id`, `password`, `reg_date`) VALUES ('$this->mctime', '$this->password', '$reg_date')";
        $this->db_con->query($sql);
        $this->db_con->close();
        // echo $this->db_con->error;
        $_SESSION['user'] = $this->mctime;
        return "注册成功！<br />您的账号ID为：$this->mctime <br />密码：您设定的密码。<br />请熟记您的用户信息！";
    }


    // 方法：登录
    public function Login($userid, $password)
    {
        // 转义
        $userid = mysqli_real_escape_string($this->db_con, $userid);
        $password = mysqli_real_escape_string($this->db_con, $password);
        // md5 password并赋值给$password
        $password = md5($password);
        // 从数据库中取出数据
        $sql = "SELECT `id`, `password` FROM `users` WHERE `id` = $userid";
        // 判断ID是否存在（方法?当然是看数量啊）
        error_reporting(0);
        if (mysqli_num_rows($this->db_con->query($sql)) > 0) {
            $result = $this->db_con->query($sql)->fetch_assoc();
            while ($row = $result) {
                $dbpwd = $row['password'];
                break;
            }
            if ($password == $dbpwd) {
                $_SESSION['user'] = $userid;
                return '登录成功，欢迎回来。';
            } else {
                return '密码不正确。';
            }
        } else {
            return '找不到对应的用户ID。';
        }
    }

    // 方法：登出
    public function userLogout()
    {
        session_destroy();
        return '*';
    }

    // 方法：列出所有记事本
    public function listNote()
    {
        // 为了数据库和浏览器性能，需要截取从数据库中的字符串。
        // error_reporting(0);
        $userid = $_SESSION['user'];
        $sql = "SELECT `id`, `title`, `add_time`, LEFT(`content`, 100), `share` FROM `notes` WHERE `by_user` = $userid ORDER BY `notes`.`add_time` DESC";
        $result = $this->db_con->query($sql);
        while ($row = mysqli_fetch_array($result)) {
            $noteid = $row['id'];
            $title = htmlspecialchars(base64_decode($row['title']));
            $content = htmlspecialchars(base64_decode($row['LEFT(`content`, 100)']));
            $add_time = $row['add_time'];
            if (!$row['share'] == NULL) {
                $share = 'blue';
            } else {
                $share = 'gray';
            }
            //             echo <<<START
            //             <a href="note.php?noteid=$noteid">
            //                 <li class="mdui-list-item mdui-ripple">
            //                     <div class="mdui-list-item-content">
            //                         <div class="mdui-list-item-title mdui-list-item-one-line texto"><span class="mdui-text-color-theme">$title</span><span style="color: gray;position: absolute; right: 15px">$share $add_time</span></div>
            //                         <div class="mdui-list-item-text mdui-list-item-two-line">$content...</div>
            //                     </div>
            //                 </li>
            //             </a>
            // START;
            echo <<<START
            <li class="mdui-list-item mdui-ripple" onclick="loadNote($noteid, '$title')">
    <div class="mdui-list-item-content">
        <div class="mdui-list-item-title mdui-list-item-one-line texto"><span class="mdui-text-color-theme">$title</span><span style="color: $share;position: absolute; right: 15px">$add_time</span></div>
        <div class="mdui-list-item-text mdui-list-item-two-line">$content...</div>
    </div>
</li>
START;
        }
        $this->db_con->close();
    }

    // 方法：列出分享的记事本
    public function listSharednote($page)
    {
        // 为了数据库和浏览器性能，需要截取从数据库中的字符串。
        // error_reporting(0);
        // 先处理一下分页
        // 每一页是$page + 50
        $page = 0;
        $total = $page + 50;
        $userid = $_SESSION['user'];
        $sql = "SELECT `id`, `title`, `add_time`, LEFT(`content`, 100), `share` FROM `notes` WHERE `share` = 1 ORDER BY `notes`.`add_time` DESC LIMIT $page, $total";
        $result = $this->db_con->query($sql);
        while ($row = mysqli_fetch_array($result)) {
            $noteid = $row['id'];
            $title = htmlspecialchars(base64_decode($row['title']));
            $content = htmlspecialchars(base64_decode($row['LEFT(`content`, 100)']));
            $add_time = $row['add_time'];
            if (!$row['share'] == NULL) {
                $share = 'blue';
            } else {
                $share = 'gray';
            }
            //             echo <<<START
            //             <a href="note.php?noteid=$noteid">
            //                 <li class="mdui-list-item mdui-ripple">
            //                     <div class="mdui-list-item-content">
            //                         <div class="mdui-list-item-title mdui-list-item-one-line texto"><span class="mdui-text-color-theme">$title</span><span style="color: gray;position: absolute; right: 15px">$share $add_time</span></div>
            //                         <div class="mdui-list-item-text mdui-list-item-two-line">$content...</div>
            //                     </div>
            //                 </li>
            //             </a>
            // START;
            echo <<<START
            <li class="mdui-list-item mdui-ripple" id="loadNote" onclick="loadShareNote($noteid, '$title')">
    <div class="mdui-list-item-content">
        <div class="mdui-list-item-title mdui-list-item-one-line texto"><span class="mdui-text-color-theme">$title</span><span style="color: $share;position: absolute; right: 15px">$add_time</span></div>
        <div class="mdui-list-item-text mdui-list-item-two-line">$content...</div>
    </div>
</li>
START;
        }
        $this->db_con->close();
    }

    // 蠢方法：列出分享的记事本++
    public function listSharednoteplus()
    {
        // 为了数据库和浏览器性能，需要截取从数据库中的字符串。
        // error_reporting(0);
        // 先处理一下分页
        // 每一页是$page -1 + 50
        $page = ($_COOKIE['page'] - 1) * 50;
        $page = mysqli_real_escape_string($this->db_con, $page);
        $total = $page - 1 + 50;
        $userid = $_SESSION['user'];
        $sql = "SELECT `id`, `title`, `add_time`, LEFT(`content`, 100), `share` FROM `notes` WHERE `share` = 1 ORDER BY `notes`.`add_time` DESC LIMIT $page, $total";
        $result = $this->db_con->query($sql);
        if (mysqli_num_rows($result) <= 0) {
            echo <<<START
            <li style="border-radius: 10px;" class="mdui-list-item mdui-ripple" onclick="loadMore()">
    <div class="mdui-list-item-content">
        <div class="mdui-list-item-title mdui-list-item-one-line texto"><span class="mdui-text-color-theme">没有更多内容了</span></div>
        <div class="mdui-list-item-text mdui-list-item-two-line">下次加载时会重设计数器。</div>
    </div>
</li>
START;
            setcookie('page', 1, time() + 1000, '/api');
        } else {
            while ($row = mysqli_fetch_array($result)) {
                $noteid = $row['id'];
                $title = htmlspecialchars(base64_decode($row['title']));
                $content = htmlspecialchars(base64_decode($row['LEFT(`content`, 100)']));
                $add_time = $row['add_time'];
                if (!$row['share'] == NULL) {
                    $share = '<span style="color:blue">SHARED</span>';
                } else {
                    $share = NULL;
                }
                echo <<<START
            <li style="border-radius: 10px;" class="mdui-list-item mdui-ripple" id="loadNote" onclick="loadShareNote($noteid, '$title')">
    <div class="mdui-list-item-content">
        <div class="mdui-list-item-title mdui-list-item-one-line texto"><span class="mdui-text-color-theme">$title</span><span style="color: gray;position: absolute; right: 15px">$share $add_time</span></div>
        <div class="mdui-list-item-text mdui-list-item-two-line">$content...</div>
    </div>
</li>
START;
            }
        }
        $this->db_con->close();
    }

    // 方法：获取记事本标题
    public function getTitle()
    {
        $userid = $_SESSION['user'];
        $this->noteid = mysqli_real_escape_string($this->db_con, $this->noteid);
        $sql = "SELECT `title`, `share`, `by_user` FROM `notes` WHERE `id` = $this->noteid";
        while ($rows = $this->db_con->query($sql)->fetch_assoc()) {
            $this->title = base64_decode($rows['title']);
            $share = $rows['share'];
            $by_user = $rows['by_user'];
            break;
        }
        if ($share == 1) {
            return $this->title;
        } else {
            if ($_SESSION['user'] == $by_user) {
                return $this->title;
            } else {
                return 'BLOCKED';
            }
        }
        $this->db_con->close();
    }

    // 方法：获取记事本内容
    public function viewNote()
    {
        $userid = $_SESSION['user'];
        $this->noteid = mysqli_real_escape_string($this->db_con, $this->noteid);
        $sql = "SELECT `content`, `share`, `by_user` FROM `notes` WHERE `id` = $this->noteid";
        while ($rows = $this->db_con->query($sql)->fetch_assoc()) {
            $this->content = base64_decode($rows['content']);
            $share = $rows['share'];
            $by_user = $rows['by_user'];
            break;
        }
        if ($share == 1) {
            return $this->content;
        } else {
            if ($_SESSION['user'] == $by_user) {
                return $this->content;
            } else {
                return 'Your request was blocked due to security issues.<br />您的请求因安全问题而被阻止。<br />可能原因如下：<ul><li>该页面不存在。</li><li>如果您之前可以访问该页面，则可能拥有者已停止共享，请联系拥有者。</li><li>如果您输入了来自其他人的图中/视频中的URL，则所有者没有共享该记事本。</li></ul><div style="display: none">请不要尝试编辑或删除属于您以外的记事本，因为您的更改将不会为对方保存。</div>';
            }
        }
        $this->db_con->close();
    }

    // 方法：新增记事本
    public function addNote($title, $content, $cgid)
    {
        $userid = $_SESSION['user'];
        $this->title = mysqli_real_escape_string($this->db_con, base64_encode($title));
        $this->content = mysqli_real_escape_string($this->db_con, base64_encode($content));
        $this->cgid = mysqli_real_escape_string($this->db_con, $cgid);
        $datetime = date('Y-m-d H:i:s');
        $sql = "INSERT INTO `notes` (`id`, `title`, `content`, `add_time`, `by_user`, `by_category`) VALUES (NULL, '$this->title', '$this->content', '$datetime', $userid, $this->cgid)";
        $this->db_con->query($sql);
        $this->db_con->close();
    }

    // 方法：更改记事本内容
    public function editNote($title, $content)
    {
        $userid = $_SESSION['user'];
        $this->title = mysqli_real_escape_string($this->db_con, base64_encode($title));
        $this->content = mysqli_real_escape_string($this->db_con, base64_encode($content));
        $sql = "UPDATE `notes` SET `title` = '$this->title' WHERE `id` = $this->noteid AND `by_user` = $userid";
        $this->db_con->query($sql);
        $sql = "UPDATE `notes` SET `content` = '$this->content' WHERE `id` = $this->noteid AND `by_user` = $userid";
        $this->db_con->query($sql);
        $this->db_con->close();
    }

    // 方法：删除记事本
    public function delNote()
    {
        $userid = $_SESSION['user'];
        $sql = "DELETE FROM `notes` WHERE `notes`.`id` = $this->noteid AND `by_user` = $userid";
        $this->db_con->query($sql);
        $this->db_con->close();
    }

    // 方法：分享记事本相关操作
    public function shareNote()
    {
        $userid = $_SESSION['user'];
        $this->noteid = mysqli_real_escape_string($this->db_con, $this->noteid);
        // 判断数据库中内容，如果已经分享则取消分享，为分享则分享。
        $sql = "SELECT `share` FROM `notes` WHERE `id` = $this->noteid AND `by_user` = $userid";
        while ($row = $this->db_con->query($sql)->fetch_assoc()) {
            if ($row['share'] == NULL) {
                // 如果为空，则共享
                $sql = "UPDATE `notes` SET `share` = 1 WHERE `notes`.`id` = $this->noteid";
                $this->db_con->query($sql);
                return '已共享并展示在分享广场中，将链接发送给其他人即可：https://imlo.li/share.php?noteid=' . $this->noteid . '<br />获取标题：https://imlo.li/api/pull.php?id=' . $this->noteid . '&action=getTitle<br />获取内容：https://imlo.li/api/pull.php?id=' . $this->noteid . '&action=getContent<br />获取双者：https://imlo.li/api/pull.php?id=' . $this->noteid;
                echo $row['share'];
            } else {
                // 取消共享
                $sql = "UPDATE `notes` SET `share` = NULL WHERE `notes`.`id` = $this->noteid";
                $this->db_con->query($sql);
                return '已取消共享，其他人无法查看该页面。';
                echo $row['share'];
            }
        }
        $this->db_con->close();
    }

    // 方法：获取记事本分享状态
    public function getShare()
    {
        $this->noteid = mysqli_real_escape_string($this->db_con, $this->noteid);
        $sql = "SELECT `share` FROM `notes` WHERE `id` = $this->noteid";
        while ($rows = $this->db_con->query($sql)->fetch_assoc()) {
            return $this->share = $rows['share'];
        }
        $this->db_con->close();
    }

    // 方法：获取ID
    public function getID()
    {
        if (empty($_SESSION['user'])) {
            return '无法获取ID，请确保您已经登录。';
        } else {
            return $_SESSION['user'];
        }
    }

    // 方法：删除账号
    public function delAcc()
    {
        session_start();
        $userid = $_SESSION['user'];
        $sql = "DELETE FROM `users` WHERE `users`.`id` = $userid";
        $this->db_con->query($sql);
        session_destroy();
        return '账号已删除。';
    }

    // 获取分类列表
    public function getCategorylist()
    {
        $userid = $_SESSION['user'];
        $sql = "SELECT `id`, `name` FROM `categorys` WHERE `by_user` = $userid";
        $result = $this->db_con->query($sql);
        while ($rows = mysqli_fetch_array($result)) {
            echo '<li class="mdui-list-item mdui-ripple"><i class="mdui-list-item-icon mdui-icon material-icons"></i><div class="mdui-list-item-content" style="padding-top:0px;padding-bottom:0px" onclick="loadCategory(' . $rows['id'] . ',\'' . $rows['name'] . '\')"> ' . $rows['name'] . '<span onclick="delCg(' . $rows['id'] . ')" style="position: absolute; top: 14%;right:10%;min-width:15%;margin:0px;padding:0px;" class="mdui-btn mdui-ripple mdui-text-color-primary"><i class="mdui-icon material-icons">close</i></span></div></li>';
            // echo '<span class="mdui-list-item mdui-ripple" onclick="loadCategory(' . $rows['id'] . ',\'' . $rows['name'] . '\')">' . $rows['name'] . '<span onclick="delCg(' . $rows['id'] . ')" style="position: absolute; right: 4%;min-width:auto" class="mdui-btn mdui-ripple mdui-text-color-primary">删除</span></span>';
        }
        $this->db_con->close();
    }

    // 获取分类列表(select)
    public function getCategorylistselect()
    {
        $userid = $_SESSION['user'];
        $sql = "SELECT `id`, `name` FROM `categorys` WHERE `by_user` = $userid";
        $result = $this->db_con->query($sql);
        while ($rows = mysqli_fetch_array($result)) {
            //<option value="2">State 2</option>
            echo '<option value="' . $rows['id'] . '">' . $rows['name'] . '</option>';
        }
        $this->db_con->close();
    }

    // 新增分类
    public function addCategory($name)
    {
        $userid = $_SESSION['user'];
        $this->name = mysqli_real_escape_string($this->db_con, $name);
        $sql = "INSERT INTO `categorys` (`id`, `name`, `by_user`) VALUES (NULL, '$name', $userid)";
        $this->db_con->query($sql);
        $this->db_con->close();
    }

    // 方法：加载分类中的Note
    public function listCg()
    {
        // 为了数据库和浏览器性能，需要截取从数据库中的字符串。
        // error_reporting(0);
        $userid = $_SESSION['user'];
        $sql = "SELECT `id`, `title`, `add_time`, LEFT(`content`, 100), `share` FROM `notes` WHERE `by_category` = $this->cgid ORDER BY `notes`.`add_time` DESC";
        $result = $this->db_con->query($sql);
        while ($row = mysqli_fetch_array($result)) {
            $noteid = $row['id'];
            $title = htmlspecialchars(base64_decode($row['title']));
            $content = htmlspecialchars(base64_decode($row['LEFT(`content`, 100)']));
            $add_time = $row['add_time'];
            if (!$row['share'] == NULL) {
                $share = 'blue';
            } else {
                $share = 'gray';
            }
            echo <<<START
            <li class="mdui-list-item mdui-ripple" onclick="loadNote($noteid, '$title')">
    <div class="mdui-list-item-content">
        <div class="mdui-list-item-title mdui-list-item-one-line texto"><span class="mdui-text-color-theme">$title</span><span style="color: $share;position: absolute; right: 15px">$add_time</span></div>
        <div class="mdui-list-item-text mdui-list-item-two-line">$content...</div>
    </div>
</li>
START;
        }
        $this->db_con->close();
    }

    // 方法：删除分类
    public function delCg()
    {
        $userid = $_SESSION['user'];
        $sql = "DELETE FROM `categorys` WHERE `categorys`.`id` = $this->cgid AND `by_user` = $userid";
        $this->db_con->query($sql);
    }

    // 方法：获取发布时间
    public function getTimedate()
    {
        $this->id = mysqli_real_escape_string($this->db_con, $this->id);
        $sql = "SELECT `add_time` FROM `notes` WHERE `id` = $this->noteid";
        while ($rows = $this->db_con->query($sql)->fetch_assoc()) {
            echo $this->add_time = $rows['add_time'];
            break;
        }
    }

    // 方法：获取组列表
    public function getGrouplist()
    {
        $userid = $_SESSION['user'];
        $sql = "SELECT `id`, `name`, `ip_port` FROM `groups` WHERE `by_user` = $userid";
        $result = $this->db_con->query($sql);
        while ($rows = mysqli_fetch_array($result)) {
            echo '<li class="mdui-list-item mdui-ripple"><i class="mdui-list-item-icon mdui-icon material-icons"></i><div class="mdui-list-item-content" style="padding-top:0px;padding-bottom:0px" onclick="loadGroup(' . $rows['id'] . ',\'' . $rows['name'] . '\',\'' . $rows['ip_port'] . '\')"> ' . $rows['name'] . '<span onclick="delGroup(' . $rows['id'] . ')" style="position: absolute; top: 14%;right:10%;min-width:15%;margin:0px;padding:0px;" class="mdui-btn mdui-ripple mdui-text-color-primary"><i class="mdui-icon material-icons">close</i></span></div></li>';
            // echo '<span class="mdui-list-item mdui-ripple" onclick="loadCategory(' . $rows['id'] . ',\'' . $rows['name'] . '\')">' . $rows['name'] . '<span onclick="delCg(' . $rows['id'] . ')" style="position: absolute; right: 4%;min-width:auto" class="mdui-btn mdui-ripple mduip_porti-text-color-primary">删除</span></span>';
        }
        $this->db_con->close();
    }

    // 新增组
    public function addGroup($name, $nickname, $ip_port, $password)
    {
        $userid = $_SESSION['user'];
        $this->name = mysqli_real_escape_string($this->db_con, $name);
        $nickname = mysqli_real_escape_string($this->db_con, $nickname);
        $ip_port = mysqli_real_escape_string($this->db_con, $ip_port);
        $password = mysqli_real_escape_string($this->db_con, $password);
        $sql = "INSERT INTO `groups` (`id`, `nickname`, `name`, `ip_port`, `password`, `by_user`) VALUES (NULL, '$nickname', '$name','$ip_port', '$password', '$userid')";
        $this->db_con->query($sql);
        $this->db_con->close();
    }

    // 获取昵称（写废了）
    public function getNickname() {
        $userid = $_SESSION['user'];
        $sql = "SELECT `nickname` FROM `groups` WHERE `by_user` = $userid";
        while ($row = mysqli_fetch_array($this->db_con->query($sql))) {
            echo $row['nickname'];
        break;
        }
        $this->db_con->close();
    }

    // 获取IP:Port
    public function getIP_Port() {
        $userid = $_SESSION['user'];
        $sql = "SELECT `ip_port` FROM `groups` WHERE `by_user` = $userid";
        while ($row = mysqli_fetch_array($this->db_con->query($sql))) {
            echo $row['ip_port'];
        break;
        }
        $this->db_con->close();
    }

    // 删除组
    public function delGroup($id) {
        $userid = $_SESSION['user'];
        $id = mysqli_real_escape_string($this->db_con, $id);
        $sql = "DELETE FROM `groups` WHERE `groups`.`id` = $id AND `by_user` = $userid";
        $this->db_con->query($sql);
        $this->db_con->close();
    }
}
