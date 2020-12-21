<h2 class="h1">Custom redirections sitemap (From <?=$from?> to <?=$to?>)</h2>
<?php

foreach($page_links as $link){ ?>

<a href="<?= $link['link_source']?>" target="_self" title="<?= $link['title']?>"><?= $link['link_source']?></a><br>

<?php }
?>