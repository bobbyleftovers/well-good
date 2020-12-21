<?php

/**
 * The admin area of the plugin to load the User List Table
 */
?>

<div class="wrap">    
    <h2>Varnish logs</h2>
        <div id="nds-wp-list-table-demo">			
            <div id="nds-post-body">		
				<form id="nds-user-list-form" method="get">
					<input type="hidden" name="page" value="<?= $_REQUEST['page'] ?>" />
					<?php 
						//$this->table->search_box('Find', 'nds-user-find');
						$this->table->display(); 
					?>					
				</form>
            </div>			
        </div>
</div>