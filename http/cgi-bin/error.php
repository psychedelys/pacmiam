<?php
// Start des sessions
session_start();
?>
<html><head><title>Hooups a problem occurs</title></head><body bgcolor=#00a>
<div style="display:block;width:80%;background:#00a;color:#fff;padding:60px;whitespace:pre;font:14px bold;font-family:fixedsys, terminal, monospace;text-align:center;">

<span style="margin:1em auto;color:#00a;background:#aaa;font:14px bold;font-family:fixedsys, terminal, monospace">
&#160;
<?php
// Print error, msg passe par sf dans la fonction
echo "Error ".$_SESSION['error'];
?>
&#160;</span>
<div style="text-align:left;"><br />
&#160;&#160;&#160;A fatal exception 0E has occurred at 0157:BF7FF831. The<br />
&#160;&#160;&#160;current application will be terminated.<br />
<br />
&#160;&#160;&#160;*&#160;&#160;Press any key to terminate the current application.<br />
&#160;&#160;&#160;*&#160;&#160;Press CTRL+ALT+DEL to restart your computer. You will<br />
&#160;&#160;&#160;&#160;&#160;&#160;lose all unsaved information in all applications.<br />
<br />
&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;Press any key to continue</div>
</div>
</body></html>
