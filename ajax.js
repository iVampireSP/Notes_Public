var $$ = mdui.$;
document.getElementById("topload").style.display = "none";
// 夜间代码 来自https://www.mdui.org/questions/196
// 提前定义好事件
var changeStyle = new CustomEvent('change_style', {
    detail: {}
});
//注册监听器
window.addEventListener('change_style', function(event) {
    $$('body').toggleClass('mdui-theme-layout-dark');
});

/**
 *
 *  Base64 encode / decode
 *
 *  @author haitao.tu
 *  @date   2010-04-26
 *  @email  tuhaitao@foxmail.com
 *
 */

function Base64() {

    // private property
    _keyStr = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";

    // public method for encoding
    this.encode = function(input) {
        var output = "";
        var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
        var i = 0;
        input = _utf8_encode(input);
        while (i < input.length) {
            chr1 = input.charCodeAt(i++);
            chr2 = input.charCodeAt(i++);
            chr3 = input.charCodeAt(i++);
            enc1 = chr1 >> 2;
            enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
            enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
            enc4 = chr3 & 63;
            if (isNaN(chr2)) {
                enc3 = enc4 = 64;
            } else if (isNaN(chr3)) {
                enc4 = 64;
            }
            output = output +
                _keyStr.charAt(enc1) + _keyStr.charAt(enc2) +
                _keyStr.charAt(enc3) + _keyStr.charAt(enc4);
        }
        return output;
    }

    // public method for decoding
    this.decode = function(input) {
        var output = "";
        var chr1, chr2, chr3;
        var enc1, enc2, enc3, enc4;
        var i = 0;
        input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");
        while (i < input.length) {
            enc1 = _keyStr.indexOf(input.charAt(i++));
            enc2 = _keyStr.indexOf(input.charAt(i++));
            enc3 = _keyStr.indexOf(input.charAt(i++));
            enc4 = _keyStr.indexOf(input.charAt(i++));
            chr1 = (enc1 << 2) | (enc2 >> 4);
            chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
            chr3 = ((enc3 & 3) << 6) | enc4;
            output = output + String.fromCharCode(chr1);
            if (enc3 != 64) {
                output = output + String.fromCharCode(chr2);
            }
            if (enc4 != 64) {
                output = output + String.fromCharCode(chr3);
            }
        }
        output = _utf8_decode(output);
        return output;
    }

    // private method for UTF-8 encoding
    _utf8_encode = function(string) {
        string = string.replace(/\r\n/g, "\n");
        var utftext = "";
        for (var n = 0; n < string.length; n++) {
            var c = string.charCodeAt(n);
            if (c < 128) {
                utftext += String.fromCharCode(c);
            } else if ((c > 127) && (c < 2048)) {
                utftext += String.fromCharCode((c >> 6) | 192);
                utftext += String.fromCharCode((c & 63) | 128);
            } else {
                utftext += String.fromCharCode((c >> 12) | 224);
                utftext += String.fromCharCode(((c >> 6) & 63) | 128);
                utftext += String.fromCharCode((c & 63) | 128);
            }

        }
        return utftext;
    }

    // private method for UTF-8 decoding
    _utf8_decode = function(utftext) {
        var string = "";
        var i = 0;
        var c = c1 = c2 = 0;
        while (i < utftext.length) {
            c = utftext.charCodeAt(i);
            if (c < 128) {
                string += String.fromCharCode(c);
                i++;
            } else if ((c > 191) && (c < 224)) {
                c2 = utftext.charCodeAt(i + 1);
                string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
                i += 2;
            } else {
                c2 = utftext.charCodeAt(i + 1);
                c3 = utftext.charCodeAt(i + 2);
                string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
                i += 3;
            }
        }
        return string;
    }
}
var b = new Base64();

// 公告
// mdui.alert('请注意：我们的许可条款已更新。<br />改动内容：分享记事本时，记事本将会出现在“分享广场”上。<br />请熟知！');



function change_style() {
    // 在对应的元素上触发该事件
    if (window.dispatchEvent) {
        window.dispatchEvent(changeStyle);
    } else {
        //ie8兼容
        window.fireEvent(changeStyle);
    }
}
// 夜间代码结束
function showloading() {
    var abutton = document.getElementById("a-but");
    // document.getElementById("topload").style.display = "block";
    if (abutton !== null) {
        document.getElementById("a-but").style.display = 'none';
    }
    document.getElementById("mainContent").style.filter = "blur(1px)";
}

function disableload() {
    var abutton = document.getElementById("a-but");
    // document.getElementById("topload").style.display = "none";
    if (abutton !== null) {
        document.getElementById("a-but").style.display = 'block';
    }
    document.getElementById("mainContent").style.filter = "unset";
}

function changeUrl(url, title) {
    var stateObject = {};
    var newUrl = url;
    document.title = title;
    history.pushState(stateObject, title, newUrl);
}
loadCategorymenu();

// 获取ID
function getID() {
    changeUrl(null, '正在获取ID...');
    showloading();
    var xmlhttp;
    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            subTitle(xmlhttp.responseText);
            mdui.alert('您的用户ID：' + xmlhttp.responseText);
            changeUrl(null, xmlhttp.responseText);
            disableload();
        }
    }
    xmlhttp.open("GET", "/api/getID.php", true);
    xmlhttp.send();
}

function sharenote(noteid) {
    showloading();
    changeUrl(null, '正在切换分享状态...');
    var xmlhttp;
    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            if (xmlhttp.responseText == null || xmlhttp.responseText == '') {
                changeUrl(null, '操作失败。');
                mdui.dialog({
                    title: '执行结果',
                    content: '操作失败。您没有足够的权限或者正在尝试越权操作。',
                    buttons: [{
                        text: '确认',
                        onClick: function(inst) {
                            changeUrl(null, '继续浏览记事本。');
                            disableload();
                        }
                    }]
                });
            } else {
                changeUrl(null, '操作已完成。');
                mdui.dialog({
                    title: '执行结果',
                    content: xmlhttp.responseText,
                    buttons: [{
                        text: '确认',
                        onClick: function(inst) {
                            changeUrl(null, '继续浏览记事本。');
                            disableload();
                        }
                    }]
                });
            }
        }
    }
    xmlhttp.open("POST", "note.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send(`noteid=${noteid}&action=share`);
}

function delAcc() {
    changeUrl(null, '正在删除账号...');
    showloading();
    var xmlhttp;
    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            subTitle('账号已删除。');
            mdui.alert('账号已删除。');
            changeUrl(null, '账号已删除。');
            disableload();
        }
    }
    xmlhttp.open("GET", "delAcc.php", true);
    xmlhttp.send();
}

function loadWelcome() {
    changeUrl(null, '正在加载欢迎界面...');
    showloading();
    var xmlhttp;
    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            subTitle('欢迎新用户');
            document.getElementById("mainContent").innerHTML = xmlhttp.responseText;
            changeUrl(null, '欢迎新用户。');
            disableload();
        }
    }
    xmlhttp.open("GET", "welcome.html", true);
    xmlhttp.send();
}


function userReg() {
    changeUrl(null, '正在注册...');
    showloading();
    var password = $('#password').val();
    // 先判断是否为空，请：
    if (password == null || password == "") {
        mdui.snackbar({
            message: '能不能好好填啊Kora!',
            position: 'bottom'
        });
        disableload();
        return false;
    }
    var xmlhttp;
    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            mdui.alert(xmlhttp.responseText);
            changeUrl('/', 'Sweet Home -> Note');
            loadWelcome();
            disableload();
        }
    }
    xmlhttp.open("POST", "register.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send(`password=${password}`);
}

function userLogin() {
    changeUrl(null, '正在登录...');
    showloading();
    var userid = $('#userid').val();
    var password = $('#password').val();
    // 先判断是否为空，请：
    if (userid == null || userid == "" || password == null || password == "") {
        mdui.snackbar({
            message: '能不能好好填啊Kora!',
            position: 'bottom'
        });
        disableload();
        return false;
    }
    var xmlhttp;
    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            changeUrl('/', 'Sweet Home -> Login');
            // window.location.replace("index.php");
            mdui.snackbar({
                message: xmlhttp.responseText,
                position: 'bottom'
            });
            loadIndex();
            disableload();
        }
    }
    xmlhttp.open("POST", "login.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send(`userid=${userid}&password=${password}`);
}

function subTitle(title) {
    document.getElementById("subTitle").innerHTML = title;
}

function loadNote(noteid, title) {
    changeUrl(null, `正在加载记事本：${title}...`);
    showloading();
    var xmlhttp;
    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            changeUrl('/note.php?noteid=' + noteid, title);
            subTitle(title);
            document.getElementById("mainContent").innerHTML = xmlhttp.responseText;
            //<li class=\"mdui-list-item mdui-ripple\"><i class=\"mdui-list-item-icon mdui-icon material-icons\">delete</i><div class=\"mdui-list-item-content\" onclick=\"loadDelnote(" + noteid + ")\">删除记事本</div></li>
            document.getElementById("menu").innerHTML = "<li class=\"mdui-list-item mdui-ripple mdui-list-item-active\"><i class=\"mdui-list-item-icon mdui-icon material-icons\">event_note</i><div class=\"mdui-list-item-content\" onclick=\"loadIndex()\">记事本</div></li><li class=\"mdui-list-item mdui-ripple\"><i class=\"mdui-list-item-icon mdui-icon material-icons\">add</i><div class=\"mdui-list-item-content\" onclick=\"loadAdd()\">新增记事本</div></li><li class=\"mdui-list-item mdui-ripple\"><i class=\"mdui-list-item-icon mdui-icon material-icons\">edit</i><div class=\"mdui-list-item-content\" onclick=\"loadEdit(" + noteid + ")\">编辑记事本</div></li><li class=\"mdui-list-item mdui-ripple\"><i class=\"mdui-list-item-icon mdui-icon material-icons\">delete</i><div class=\"mdui-list-item-content\" onclick=\"loadDelnote(" + noteid + ")\">删除记事本</div></li><li class=\"mdui-list-item mdui-ripple\"><i class=\"mdui-list-item-icon mdui-icon material-icons\">share</i><div class=\"mdui-list-item-content\" onclick=\"loadShareground()\">分享广场</div></li>";
            disableload();
        }
    }
    xmlhttp.open("GET", "note.php?noteid=" + noteid, true);
    xmlhttp.send();
}

function loadIndex() {
    changeUrl(null, '正在加载概览...');
    showloading();
    var xmlhttp;
    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            changeUrl('/', 'Sweet Home -> Note');
            subTitle('记事本');
            document.getElementById("mainContent").innerHTML = xmlhttp.responseText;
            document.getElementById("menu").innerHTML = "<li class=\"mdui-list-item mdui-ripple mdui-list-item-active\"><i class=\"mdui-list-item-icon mdui-icon material-icons\">event_note</i><div class=\"mdui-list-item-content\" onclick=\"loadIndex()\">记事本</div></li><li class=\"mdui-list-item mdui-ripple\"><i class=\"mdui-list-item-icon mdui-icon material-icons\">add</i><div class=\"mdui-list-item-content\" onclick=\"loadAdd()\">新增记事本</div></li><li class=\"mdui-list-item mdui-ripple\"><i class=\"mdui-list-item-icon mdui-icon material-icons\">share</i><div class=\"mdui-list-item-content\" onclick=\"loadShareground()\">分享广场</div></li>";
            disableload();
        }
    }
    xmlhttp.open("GET", "main.php", true);
    xmlhttp.send();
}

function loadAdd() {
    changeUrl(null, '正在加载编辑器...');
    showloading();
    var xmlhttp;
    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            subTitle('新增记事本');
            changeUrl('/', 'Sweet Home -> New');
            document.getElementById("mainContent").innerHTML = xmlhttp.responseText;
            document.getElementById("menu").innerHTML = "<li class=\"mdui-list-item mdui-ripple\"><i class=\"mdui-list-item-icon mdui-icon material-icons\">event_note</i><div class=\"mdui-list-item-content\" onclick=\"loadIndex()\">记事本</div></li><li class=\"mdui-list-item mdui-ripple mdui-list-item-active\"><i class=\"mdui-list-item-icon mdui-icon material-icons\">add</i><div class=\"mdui-list-item-content\" onclick=\"loadAdd()\">新增记事本</div></li><li class=\"mdui-list-item mdui-ripple\"><i class=\"mdui-list-item-icon mdui-icon material-icons\">share</i><div class=\"mdui-list-item-content\" onclick=\"loadShareground()\">分享广场</div></li>";
            disableload();
        }
    }
    xmlhttp.open("GET", "addnote.php", true);
    xmlhttp.send();
}

function newNote() {
    changeUrl(null, '正在添加记事本...');
    showloading();
    setTimeout(function() {
        changeUrl(null, '正在加载概览.');
        setTimeout(function() {
            changeUrl(null, '正在加载概览..');
            setTimeout(function() {
                changeUrl(null, '正在加载概览...');
                setTimeout(function() {
                    changeUrl(null, 'Sweet Home -> Note');
                }, 200);
            }, 200);
        }, 200);
    }, 200);
    var title = b.encode($('#title').val());
    var content = b.encode($('#content').val());
    var cgid = $('#cgid').val();

    // 先判断是否为空，请：
    if (title == null || content == "" || title == null || content == "" || cgid == null || cgid == "") {
        mdui.snackbar({
            message: '能不能好好填啊Kora!',
            position: 'bottom'
        });
        changeUrl(null, '能不能好好填啊Kora!');
        setTimeout(function() {
            changeUrl(null, 'Sweet Home -> New');
        }, 2000);
        disableload();
        return false;
    }
    var xmlhttp;
    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            mdui.snackbar({
                message: xmlhttp.responseText,
                position: 'bottom'
            });
            loadIndex();
            disableload();
        }
    }
    xmlhttp.open("POST", "addnote.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send(`title=${title}&content=${content}&cgid=${cgid}`);
}

function loadEdit(noteid) {
    changeUrl(null, '正在加载编辑器...');
    showloading();
    var xmlhttp;
    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            subTitle('编辑记事本');
            changeUrl('/', 'Sweet Home -> Edit');
            document.getElementById("mainContent").innerHTML = xmlhttp.responseText;
            document.getElementById("menu").innerHTML = "<li class=\"mdui-list-item mdui-ripple\"><i class=\"mdui-list-item-icon mdui-icon material-icons\">event_note</i><div class=\"mdui-list-item-content\" onclick=\"loadIndex()\">记事本</div></li><li class=\"mdui-list-item mdui-ripple\"><i class=\"mdui-list-item-icon mdui-icon material-icons\">add</i><div class=\"mdui-list-item-content\" onclick=\"loadAdd()\">新增记事本</div></li><li class=\"mdui-list-item mdui-ripple mdui-list-item-active\"><i class=\"mdui-list-item-icon mdui-icon material-icons\">edit</i><div class=\"mdui-list-item-content\" onclick=\"loadEdit(" + noteid + ")\">编辑记事本</div></li><li class=\"mdui-list-item mdui-ripple\"><i class=\"mdui-list-item-icon mdui-icon material-icons\">delete</i><div class=\"mdui-list-item-content\" onclick=\"loadDelnote(" + noteid + ")\">删除记事本</div></li><li class=\"mdui-list-item mdui-ripple\"><i class=\"mdui-list-item-icon mdui-icon material-icons\">share</i><div class=\"mdui-list-item-content\" onclick=\"loadShareground()\">分享广场</div></li>";
            disableload();

        }
    }
    xmlhttp.open("GET", "editnote.php?noteid=" + noteid, true);
    xmlhttp.send();
}

function editNote(noteid) {
    changeUrl(null, '正在提交更改...');
    showloading();
    var title = b.encode($('#title').val());
    var content = b.encode($('#content').val());
    // 先判断是否为空，请：
    if (title == null || content == "" || title == null || content == "") {
        mdui.snackbar({
            message: '能不能好好填啊Kora!',
            position: 'bottom'
        });
        changeUrl(null, '能不能好好填啊Kora!');
        setTimeout(function() {
            changeUrl(null, 'Sweet Home -> Edit');
        }, 2000);
        return false;
    }
    var xmlhttp;
    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            changeUrl(null, '所选的记事本内容已被更改。');
            mdui.snackbar({
                message: '记事本内容已更新。',
                position: 'bottom'
            });
            loadNote(noteid, title);
            disableload();
        }
    }
    xmlhttp.open("POST", "editnote.php?noteid=" + noteid, true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send(`title=${title}&content=${content}`);
}

function delNote(noteid) {
    changeUrl(null, `正在删除: ${noteid}...`);
    showloading();
    var xmlhttp;
    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            mdui.snackbar({
                message: '已删除。',
                position: 'bottom'
            });
            changeUrl('/', 'Sweet Home -> Note');
            loadIndex();
            disableload();
        }
    }
    xmlhttp.open("GET", "delnote.php?noteid=" + noteid, true);
    xmlhttp.send();
}

function loadDelnote(noteid) {
    mdui.dialog({
        title: '确认删除？',
        content: '您当前正在删除一个记事本，是否确认？',
        buttons: [{
                text: '取消'
            },
            {
                text: '确认',
                onClick: function(inst) {
                    showloading();
                    delNote(noteid);
                    disableload();
                }
            }
        ]
    });
}

function loadCategorymenu() {
    var xmlhttp;
    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById("categorys").innerHTML = xmlhttp.responseText;
        }
    }
    xmlhttp.open("GET", "api/getCategory.php", true);
    xmlhttp.send();
}

function loadAddCategory() {
    changeUrl(null, '正在加载编辑器...');
    showloading();
    var xmlhttp;
    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            subTitle('新增分类');
            changeUrl('/', 'Sweet Home -> New Category');
            document.getElementById("mainContent").innerHTML = xmlhttp.responseText;
            disableload();
        }
    }
    xmlhttp.open("GET", "addCategory.php", true);
    xmlhttp.send();
}

function newCategory() {
    changeUrl(null, 'Sweet Home');
    showloading();
    var name = $('#name').val();
    // 先判断是否为空，请：
    if (name == null || name == "") {
        mdui.snackbar({
            message: '能不能好好填啊Kora!',
            position: 'bottom'
        });
        changeUrl(null, '能不能好好填啊Kora!');
        setTimeout(function() {
            changeUrl(null, 'Sweet Home -> New Category');
        }, 2000);
        disableload();
        return false;
    }
    var xmlhttp;
    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            mdui.snackbar({
                message: '分类添加成功。',
                position: 'bottom'
            });
            loadCategorymenu();
            disableload();
        }
    }
    xmlhttp.open("POST", "addCategory.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send(`name=${name}`);
}


// 加载分类内容
function loadCategory(id, name) {
    changeUrl(null, name);
    showloading();
    var xmlhttp;
    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            subTitle(name);
            document.getElementById("mainContent").innerHTML = xmlhttp.responseText;
            disableload();
        }
    }
    xmlhttp.open("GET", "category.php?id=" + id, true);
    xmlhttp.send();
}

function delCg(id) {
    changeUrl(null, '删除分类：' + id);
    showloading();
    var xmlhttp;
    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            loadCategorymenu();
            disableload();
        }
    }
    xmlhttp.open("GET", "delCg.php?id=" + id, true);
    xmlhttp.send();
}

function loadMore() {
    showloading();
    var xmlhttp;
    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById('loadMore-btn').parentNode.removeChild(document.getElementById('loadMore-btn'));
            document.getElementById('loadMore-btn').parentNode.removeChild(document.getElementById('loadMore-btn'));
            document.getElementById("mainContent").innerHTML = document.getElementById("mainContent").innerHTML + xmlhttp.responseText;
            disableload();
        }
    }
    xmlhttp.open("GET", "api/getSharepageplus.php", true);
    xmlhttp.send();
}


// Shareground.php

function loadShareground() {
    showloading();
    var xmlhttp;
    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            subTitle('分享广场');
            document.getElementById("mainContent").innerHTML = xmlhttp.responseText;
            document.getElementById("menu").innerHTML = "<li class=\"mdui-list-item mdui-ripple\"><i class=\"mdui-list-item-icon mdui-icon material-icons\">event_note</i><div class=\"mdui-list-item-content\" onclick=\"loadIndex()\">记事本</div></li><li class=\"mdui-list-item mdui-ripple\"><i class=\"mdui-list-item-icon mdui-icon material-icons\">add</i><div class=\"mdui-list-item-content\" onclick=\"loadAdd()\">新增记事本</div></li><li class=\"mdui-list-item mdui-ripple mdui-list-item-active\"><i class=\"mdui-list-item-icon mdui-icon material-icons\">share</i><div class=\"mdui-list-item-content\" onclick=\"loadShareground()\">分享广场</div></li>";
            loadMore();
            disableload();
        }
    }
    xmlhttp.open("GET", "shareground.php", true);
    xmlhttp.send();
}

function loadBar() {
    loadCategorymenu();
    loadGroupmenu();
}

// 组
loadGroupmenu();

function loadGroupmenu() {
    var xmlhttp;
    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById("groups").innerHTML = xmlhttp.responseText;
        }
    }
    xmlhttp.open("GET", "api/getGroups.php", true);
    xmlhttp.send();
}

function loadAddGroup() {
    changeUrl(null, '正在加载编辑器...');
    showloading();
    var xmlhttp;
    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            subTitle('新增组');
            changeUrl('/', 'Sweet Home -> New Group');
            document.getElementById("mainContent").innerHTML = xmlhttp.responseText;
            disableload();
        }
    }
    xmlhttp.open("GET", "addGroup.php", true);
    xmlhttp.send();
}

function newGroup() {
    changeUrl(null, 'Sweet Home');
    showloading();
    var nickname = $('#nickname').val();
    var name = $('#name').val();
    var ip_port = $('#group_ip_port').val();
    var pwd = $('#group_pwd').val();
    // 先判断是否为空，请：
    if (name == null || name == "" || nickname == null || nickname == "" || ip_port == null || ip_port == "" || pwd == null || pwd == "") {
        mdui.snackbar({
            message: '能不能好好填啊Kora!',
            position: 'bottom'
        });
        changeUrl(null, '能不能好好填啊Kora!');
        setTimeout(function() {
            changeUrl(null, 'Sweet Home -> New Group');
        }, 2000);
        disableload();
        return false;
    }
    var xmlhttp;
    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            mdui.snackbar({
                message: '组添加成功。',
                position: 'bottom'
            });
            loadGroupmenu();
            disableload();
        }
    }
    xmlhttp.open("POST", "addGroup.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send(`name=${name}&nickname=${nickname}&ip_port=${ip_port}&password=${pwd}`);
}


// 加载组内容
function loadGroup(id, name, ip_port) {
    changeUrl(null, name);
    showloading();
    var xmlhttp;
    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            subTitle(name);
            document.getElementById("mainContent").innerHTML = xmlhttp.responseText;
            disableload();
            Connect_Group(ip_port);
        }
    }
    xmlhttp.open("GET", "group.php?id=" + id, true);
    xmlhttp.send();
}

function delGroup(id) {
    changeUrl(null, '删除组：' + id);
    showloading();
    var xmlhttp;
    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            loadGroupmenu();
            loadIndex();
            disableload();
        }
    }
    xmlhttp.open("GET", "delGroup.php?id=" + id, true);
    xmlhttp.send();
}

// 加载组编辑器
function loadGroupEditor() {
    changeUrl(null, '正在加载组编辑器...');
    showloading();
    var xmlhttp;
    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            subTitle('组记事本');
            changeUrl('/', 'Sweet Home -> New');
            document.getElementById("mainContent").innerHTML = xmlhttp.responseText;
            document.getElementById("menu").innerHTML = "<li class=\"mdui-list-item mdui-ripple\"><i class=\"mdui-list-item-icon mdui-icon material-icons\">event_note</i><div class=\"mdui-list-item-content\" onclick=\"loadIndex()\">记事本</div></li><li class=\"mdui-list-item mdui-ripple mdui-list-item-active\"><i class=\"mdui-list-item-icon mdui-icon material-icons\">add</i><div class=\"mdui-list-item-content\" onclick=\"loadAdd()\">新增记事本</div></li><li class=\"mdui-list-item mdui-ripple\"><i class=\"mdui-list-item-icon mdui-icon material-icons\">share</i><div class=\"mdui-list-item-content\" onclick=\"loadShareground()\">分享广场</div></li>";
            disableload();
            // #content变化时提交信息
            $("#content").change(function() {
                ws.send($('#content').val());
            });
            // 实时接收

        }
    }
    xmlhttp.open("GET", "addnote.php", true);
    xmlhttp.send();
}

// 组实时编辑
function Connect_Group(ip_port) {
    ws = new WebSocket("ws://" + ip_port);
    ws.onopen = function() {
        mdui.snackbar({
            message: '尝试与组服务器建立连接',
            position: 'bottom'
        });
        loadGroupEditor();
    };
    ws.onclose = function(event) {
        mdui.snackbar({
            message: '与组服务器断开，请及时保存。',
            position: 'bottom'
        });
    };
    ws.onmessage = function(e) {
        $('#content').val(e.data);
        mdui.snackbar({
            message: '内容有更新',
            position: 'bottom'
        });
    };
}