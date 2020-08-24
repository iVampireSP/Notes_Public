<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    return '*';
}
require_once('config/config.php');
?>
<button class="mdui-btn" style="position: relative;top:10px" onclick="loadIndex();"><i class="mdui-icon material-icons">arrow_back</i>返回首页</button><a href="add_note_markdown.php" style="position: relative;top:10px;left:1rem" class="mdui-btn">或者启动Markdown编辑器</a>
<h1 style="font-weight: 400;"><span style="font-size: xxx-large;">新</span>记事本</h1>
<form name="addnote">
    <div class="mdui-textfield mdui-textfield-floating-label">
        <label class="mdui-textfield-label">标题</label>
        <input id="title" class="mdui-textfield-input" type="text" autocomplete="off" autofocus required />
    </div>
    <div class="mdui-textfield">
        <label class="mdui-textfield-label">内容</label>
        <textarea id="content" class="mdui-textfield-input" rows="25" placeholder="I'm Lo.li 我是洛丽。今天也要元气满满！" autocomplete="off"></textarea>
    </div>
    选择分类：
    <select class="mdui-select" name="cgid" id="cgid" mdui-select required>
        <?php $user->getCategorylistselect(); ?>
    </select>
    <br /><br /><br />
    <div class="mdui-col">
        <span id="a-but" onclick="newNote()" class="mdui-fab mdui-fab-fixed mdui-color-theme-accent"><i class="mdui-icon material-icons">add</i></span>
    </div>
</form>