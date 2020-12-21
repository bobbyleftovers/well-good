<?php
$id = get_the_id();
$data = json_encode( get_permutive_data($id), JSON_HEX_APOS|JSON_UNESCAPED_SLASHES );
?>

<script>
var permutiveData = <?= $data; ?>;

function createUUID() {
    var pow = Math.pow(10, 10);
    var uuid = Math.floor(Math.random() * pow) + '.' + Math.floor(Math.random() * pow);
    return uuid;
}
function findPPID() {
    if (!localStorage.getItem('ppid')) {
        ppid = createUUID();
        localStorage.setItem('ppid', ppid);
        return ppid;
    } else {
        return localStorage.getItem('ppid');
    }
}
var ppid = findPPID() || '';

!function(n,e,o,r,i){if(!e){e=e||{},window.permutive=e,e.q=[],e.config=i||{},e.config.projectId=o,e.config.apiKey=r,e.config.environment=e.config.environment||"production";for(var t=["addon","identify","track","trigger","query","segment","segments","ready","on","once","user","consent"],c=0;c<t.length;c++){var f=t[c];e[f]=function(n){return function(){var o=Array.prototype.slice.call(arguments,0);e.q.push({functionName:n,arguments:o})}}(f)}}}(document,window.permutive,"5814efa5-d41d-4a89-b176-1cc26fae87cd","e4ecf9e0-0a2f-42d6-a720-8fff2402c221",{});  
window.googletag=window.googletag||{},window.googletag.cmd=window.googletag.cmd||[],window.googletag.cmd.push(function(){if(0===window.googletag.pubads().getTargeting("permutive").length){var g=window.localStorage.getItem("_pdfps");window.googletag.pubads().setTargeting("permutive",g?JSON.parse(g):[])}});

window.permutive.readyWithTimeout=function(e,i,t){var u=!1,n=function(){u||(e(),u=!0)};(t=t||1/0)!==1/0&&window.setTimeout(n,t),permutive.ready(n,i)}

permutiveData.page.user.lg_uuid = ppid;

if (permutiveData.page.user.user_id) {
    permutive.identify(permutiveData.page.user.user_id.toString())
}
window.permutive.addon('web', permutiveData);
</script>
<script async src="https://cdn.permutive.com/5814efa5-d41d-4a89-b176-1cc26fae87cd-web.js"></script>
