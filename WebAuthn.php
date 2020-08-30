<?php
require_once 'config/database.php';
require_once 'WebAuthn/WebAuthn.php';
$table="webauthn";
function bin2uuid($bin)
{
    $uuidReadable = unpack("H*", $bin);
    $uuidReadable = preg_replace("/([0-9a-f]{8})([0-9a-f]{4})([0-9a-f]{4})([0-9a-f]{4})([0-9a-f]{12})/", "$1-$2-$3-$4-$5", $uuidReadable);
    $uuidReadable = array_merge($uuidReadable)[0];
    return $uuidReadable;
}

function array_decode(&$item)
{
    $item = base64_decode($item);
}

$timeout = 180;
//dont ask for attestation, it's just slowing us down.
$formats = array('none');

$WebAuthn = new \WebAuthn\WebAuthn('My1s Webauthn Sandbox', $_SERVER["HTTP_HOST"], $formats);

if (isset($_POST["reg"])) {
    $rk = (isset($_POST["rk"]));
    $uv = (isset($_POST["rk"]) ? "required" : $_POST["uv"]);
    $uid = $_POST["uid"];
    $uname = $_POST["uname"];
    $dname = $_POST['dname'];
    $dbuid = mysqli_real_escape_string($db_con, $uid);
    $q = "select credid from $table where uid='$dbuid'";
    $res = mysqli_fetch_all($db_con->query($q), MYSQLI_ASSOC);
    $exist = array_column($res, "credid");
    array_walk($exist, 'array_decode');
    $args = $WebAuthn->getCreateArgs($uid, $uname, $dname, $timeout, $rk, $uv, $exist);
    $pargs = json_encode($args, JSON_PRETTY_PRINT);
    $createArgs = json_encode($args);

    session_start();
    $_SESSION['my1sb'] = array("c" => $WebAuthn->getChallenge(), "uid" => $uid, "uv" => $uv);
} elseif (isset($_POST["regdata"])) {
    session_start();
    $r = json_decode($_POST["regdata"]);
    $challenge = $_SESSION["my1sb"]["c"];
    $uid = $_SESSION["my1sb"]["uid"];
    $uv = $_SESSION["my1sb"]["uv"];
    //die($_SESSION["my1sb"]["uv"]);
    $clientDataJSON = base64_decode($r->clientDataJSON);
    $attestationObject = base64_decode($r->attestationObject);
    $data = $WebAuthn->processCreate($clientDataJSON, $attestationObject, $challenge, ($uv === "required"));
    $data->credentialId = base64_encode($data->credentialId);
    $data->AAGUID = bin2uuid($data->AAGUID);
    $data->signatureCounter = ($data->signatureCounter === NULL ? 0 : $data->signatureCounter);
    $flags = ord($attestationObject[32]);
    echo decbin($flags);
    //var_dump($data);
    $cols = "uid,credid,pk" . ($data->signatureCounter ? ",counter" : '') . ($data->certificate ? ",cert" : '') . ($data->AAGUID !== "00000000-0000-0000-0000-000000000000" ? ",aaguid" : '');
    $dbuid = mysqli_real_escape_string($db_con, $uid);
    $vals = "'$dbuid','{$data->credentialId}','{$data->credentialPublicKey}'" . ($data->signatureCounter ? ",'{$data->signatureCounter}'" : '') . ($data->certificate ? ",'{$data->certificate}'" : '') . ($data->AAGUID !== "00000000-0000-0000-0000-000000000000" ? ",'{$data->AAGUID}'" : '');

    $q = "insert into $table ($cols) values ($vals)";
    //echo $q;
    mysqli_query($db_con, $q) or die(mysqli_error($db_con));
} elseif (isset($_POST["sig"])) {
    $rk = (isset($_POST["rk"]));
    $uv = (isset($_POST["rk"]) ? "required" : $_POST["uv"]); //if rk is set most client force UV anyway so let's just do the same
    $uid = (isset($_POST["rk"]) ? "" : $_POST["uid"]);
    $uvov = (isset($_POST["rk"]) ? false : isset($_POST["uvoverride"]));
    if ($uid) {
        $dbuid = mysqli_real_escape_string($db_con, $uid);
        $sql = "SELECT `credid` FROM `$table` WHERE `uid` = '$dbuid'";
        $result = $db_con->query($sql);
        $res = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $exist = array_column($res, "credid");
        array_walk($exist, 'array_decode');
    } else {
        $exist = [];
    }
    $args = $WebAuthn->getGetArgs($exist, $timeout, true, true, true, true, $uv);
    $pargs = json_encode($args, JSON_PRETTY_PRINT);
    $getArgs = json_encode($args);
    /*
    var_dump($getArgs);
    die();
    //*/
    session_start();
    $_SESSION['webauthn_challenge'] = array("c" => $WebAuthn->getChallenge(), "uid" => $uid, "uv" => $uv, "uvov" => $uvov);
} elseif (isset($_POST["sigdata"])) {
    session_start();
    $r = json_decode($_POST["sigdata"]);
    $challenge = $_SESSION["webauthn_challenge"]["c"];
    $uid = $_SESSION["webauthn_challenge"]["uid"];
    $uv = $_SESSION["webauthn_challenge"]["uv"];
    $uvov = $_SESSION["webauthn_challenge"]["uvov"];
    $clientDataJSON = base64_decode($r->clientDataJSON);
    $signature = base64_decode($r->signature);
    $authenticatorData = base64_decode($r->authenticatorData);
    $cid = $r->id;
    $dbcid = mysqli_real_escape_string($db_con, $cid);
    $q = "SELECT `pk`, `counter`, `uid`, `uv` FROM `$table` WHERE `credid` = '$dbcid'";
    //echo $q;
    $array = mysqli_fetch_array($db_con->query($q), MYSQLI_ASSOC);
    //var_dump($array);
    //*
    $flags = ord($authenticatorData[32]);
    $trueuv = !!($flags & 4);
    if (!$uvov) {
        if ($array['uv'] == true) {
            $uv = "required";
            if (!$trueuv)
                echo "<p>UV protection triggered</p>";
        }
    }
    //echo $uv;
    try {
        $res = $WebAuthn->processGet($clientDataJSON, $authenticatorData, $signature, $array['pk'], $challenge, $array["counter"], ($uv === "required"));
    } catch (Exception $e) {
        echo "<p>an Error occured: {$e->getMessage()}";
    }
    if ($res) {
        // update counter
        $ctrup = mysqli_query($db_con, "update $table set counter={$WebAuthn->getSignatureCounter()} where credid='$dbcid'");
        if ($trueuv) {
            // create an entry for UV in the db
            $ctrup = mysqli_query($db_con, "update webauthn set uv=true where credid='$dbcid'") or die(mysqli_error($db_con));
        }
        if (!$ctrup) {
            echo "<p>Error Updating Counter.</p>";
        }
        $flags = ord($authenticatorData[32]);
        $tuvstring = ($trueuv ? 'true' : 'false');
        echo "<p>awesome! {$array['uid']} logged in successfully!<br>
                Counter is {$WebAuthn->getSignatureCounter()}.<br>
                True UV is $tuvstring</p>";
    }
    //*/
}
require_once('config/config.php');
require_once('config/theme.php');
echo <<<end
<!DOCTYPE html>
<html>

<head>
end;
mduiHead('WebAuthn');
echo <<<end
<style type="text/css">
        img {
            width: 99.5%;
        }

        .texto {
            width: 50%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        #menu .mdui-list-item,
        .mdui-collapse-item-header {
            border-radius: 0px 50px 50px 0px;
        }

        #categorys .mdui-list-item,
        .mdui-collapse-item-header {
            border-radius: 0px 50px 50px 0px;
        }

        ul .mdui-list-item {
            border-radius: 10px;
        }

        .mdui-dialog {
            border-radius: 7px;
        }

        .mdui-btn {
            border-radius: 5px;
        }

        .mdui-appbar {
            box-shadow: 0 1px 6px 0 rgba(32, 33, 36, .28);
        }

        #menu .mdui-list-item-content {
            font-size: unset
        }

        #categorys .mdui-list-item-content {
            font-size: unset
        }

        .mdui-list-item-active {
            color: rgb(27, 116, 232);
        }

        .mdui-list-item-active * {
            color: rgb(27, 116, 232);
        }

        img {
            width: 99.5%;
        }

        h1 {
            font-weight: 300;
        }

        .mdui-card-primary,
        .mdui-card-primary-title,
        .mdui-card-primary-subtitle,
        .mdui-card-content {
            margin: 0 10px 0 5px;
        }
        </style>
</head>


<form id="start" method="post">

<div class="mdui-textfield mdui-textfield-floating-label">
  <label class="mdui-textfield-label">用户名</label>
  <input class="mdui-textfield-input" type="text" name="uname" />
</div>

<div class="mdui-textfield mdui-textfield-floating-label">
  <label class="mdui-textfield-label">昵称</label>
  <input class="mdui-textfield-input" type="text" name="dname" />
</div>

<div class="mdui-textfield mdui-textfield-floating-label">
  <label class="mdui-textfield-label">用户ID</label>
  <input class="mdui-textfield-input" type="text" name="uid" />
</div>

<br />


end;

mduiBody();
mduiHeader('注册登录器');
mduiMenu();

echo <<<end

<div class="mdui-col">
Use Resident Key (forces UV)&nbsp;&nbsp;
      <label class="mdui-switch">
        <input type="checkbox" id="rkcheck" name="rk" />
        <i class="mdui-switch-icon"></i>
      </label>
</div>
<div class="mdui-col">
Override protection against turning off UV&nbsp;&nbsp;
      <label class="mdui-switch">
        <input type="checkbox" id="uvoverride" name="uvoverride" />
        <i class="mdui-switch-icon"></i>
      </label>
</div>


<span>User Verification (PIN, Fingerprint, etc)</span><br />
<select class="mdui-select" mdui-select>
  <option value="discouraged">discouraged, (try not to verify)</option>
  <option value="preferred">preferred (same as no pamameter, if UV is available, use it)</option>
  <option value="required">required (explains itself)</option>
</select>


<br /><br /><br />
<button class="mdui-btn mdui-btn-raised mdui-ripple mdui-color-theme-accent" type="submit" name="sig">登录</button>&nbsp;
<button class="mdui-btn mdui-btn-raised mdui-ripple mdui-color-theme-accent" type="submit" name="reg">注册</button>

</form>




end;
if (isset($createArgs)) {
    echo <<<end

    <br /><br /><br />
<button class="mdui-btn mdui-btn-raised mdui-ripple mdui-color-theme-accent" onclick="webreg()">重新注册</button>


<form id="regform" method="post">
<input type="text size="100" name="regdata" id="regdata" readonly />
</form>

<pre>$pargs</pre>

<script>
var args=$createArgs;

function recursiveBase64StrToArrayBuffer(r){if("object"==typeof r)for(let t in r)if("string"==typeof r[t]){let n=r[t];if("?BINARY?B?"===n.substring(0,"?BINARY?B?".length)&&"?="===n.substring(n.length-"?=".length)){n=n.substring("?BINARY?B?".length,n.length-"?=".length);let f=window.atob(n),o=f.length,i=new Uint8Array(o);for(var e=0;e<o;e++)i[e]=f.charCodeAt(e);r[t]=i.buffer}}else recursiveBase64StrToArrayBuffer(r[t])}function arrayBufferToBase64(r){for(var e="",t=new Uint8Array(r),n=t.byteLength,f=0;f<n;f++)e+=String.fromCharCode(t[f]);return window.btoa(e)}

recursiveBase64StrToArrayBuffer(args);
function webreg() {
    navigator.credentials.create(args)
        .then(result => {
            r={};
            r.clientDataJSON = result.response.clientDataJSON  ? arrayBufferToBase64(result.response.clientDataJSON) : null;
            r.attestationObject = result.response.attestationObject ? arrayBufferToBase64(result.response.attestationObject) : null;
            document.getElementById("regdata").value=JSON.stringify(r);
            document.getElementById("regform").submit();
        })
        .catch(e => {
            window.exc=e;
            console.log(e.message);
        });
}
webreg();
</script>
end;
} elseif (isset($getArgs)) {
    echo <<<end

<button class="button" onclick="websig()">重新使用WebAuthn登录</button>


<form id="sigform" method="post">
<input type="text size="100" name="sigdata" id="sigdata" readonly />
</form>

<pre>$pargs</pre>

<script>
var args=$getArgs;

function recursiveBase64StrToArrayBuffer(r){if("object"==typeof r)for(let t in r)if("string"==typeof r[t]){let n=r[t];if("?BINARY?B?"===n.substring(0,"?BINARY?B?".length)&&"?="===n.substring(n.length-"?=".length)){n=n.substring("?BINARY?B?".length,n.length-"?=".length);let f=window.atob(n),o=f.length,i=new Uint8Array(o);for(var e=0;e<o;e++)i[e]=f.charCodeAt(e);r[t]=i.buffer}}else recursiveBase64StrToArrayBuffer(r[t])}function arrayBufferToBase64(r){for(var e="",t=new Uint8Array(r),n=t.byteLength,f=0;f<n;f++)e+=String.fromCharCode(t[f]);return window.btoa(e)}

recursiveBase64StrToArrayBuffer(args);
function websig() {
    navigator.credentials.get(args)
        .then(result => {
            r={};
            r.clientDataJSON = result.response.clientDataJSON  ? arrayBufferToBase64(result.response.clientDataJSON) : null;
            r.authenticatorData = result.response.authenticatorData ? arrayBufferToBase64(result.response.authenticatorData) : null;
            r.signature = result.response.signature ? arrayBufferToBase64(result.response.signature) : null;
            r.id = result.rawId ? arrayBufferToBase64(result.rawId) : null;
            document.getElementById("sigdata").value=JSON.stringify(r);
            document.getElementById("sigform").submit();
        })
        .catch(e => {
            window.exc=e;
            console.log(e.message);
        });
}
websig();
</script>
end;
}

mduiFooter();
echo <<<end
</body>

</html>
end;
