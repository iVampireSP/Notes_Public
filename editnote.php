<?php
session_start();
require_once('config/config.php');
$user->noteid = $_REQUEST['noteid'];
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    return '*';
}
if (!isset($_GET['noteid'])) {
    header("Location: index.php");
    return '*';
}
?>
<button class="mdui-btn" style="position: relative;top:10px"  onclick="loadIndex();"><i class="mdui-icon material-icons">arrow_back</i>返回首页</button><a href="edit_note_markdown.php?noteid=<?php echo $_GET['noteid']; ?>" style="position: relative;top:10px;left:1rem" class="mdui-btn">或者启动Markdown编辑器</a>
<form name="editnote" id="editnote-form">
    <div class="mdui-textfield">
        <label class="mdui-textfield-label">标题</label>
        <input id="title" class="mdui-textfield-input" type="text" autocomplete="off" value="<?php echo $user->getTitle(); ?>" autofocus required />
    </div>
    <div class="mdui-textfield">
        <label class="mdui-textfield-label">内容</label>
        <textarea id="content" class="mdui-textfield-input" rows="25" placeholder="I'm Lo.li 我是洛丽。今天也要元气满满！" autocomplete="off"><?php echo $user->viewNote(); ?></textarea>
    </div>
    <div class="mdui-col">
    <span id="a-but" onclick="editNote(<?php echo $user->noteid; ?>)" class="mdui-fab mdui-fab-fixed mdui-color-theme-accent"><i class="mdui-icon material-icons">save</i></span>
    </div>
</form>