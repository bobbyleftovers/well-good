<span class="text-center sm:text-left acf-stroke-text font-display <?=$is_editor ? 'is-editor':''?>">
  <?php 
    if($is_editor) include('stroke-text.inner.php');
    else echo strip_tags($innerHTML); 
  ?>
</span>