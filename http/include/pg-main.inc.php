<?php if (!isset($included)) die();  ?>
<div id="container">
<div class="wrapper">

<h1>MAin Page</h1>
<a href='./?pg=dspresto'>Display resto</a>
<?php if (isset($_SESSION['uid'])) {
	//le user est logge
	?>
	<a href='./?pg=crtmiam'>Create Miam</a>
	<a href='./?pg=crtresto'>add resto</a>
	<a href='./?pg=dspmiam'>display miam</a>
	<a href='./?pg=crtgrp'>Create Group</a> 
	<br><br>
	<h1>Coming Miam's</h1>
	<h1>My Group</h1>
<?php
} ?>

</div>
</div>
