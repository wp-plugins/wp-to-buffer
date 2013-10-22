<?php
if (isset($this->plugin->upgradeReasons) AND is_array($this->plugin->upgradeReasons) AND count($this->plugin->upgradeReasons) > 0) {
	?>
	<div class="postbox">
	    <h3 class="hndle"><?php _e('Upgrade to Pro', $this->plugin->name); ?></h3>
		
		<div class="option">
	    	<ul>
	    	<?php
	    	foreach ($this->plugin->upgradeReasons as $reasonArr) {
	    		?>
	    		<li><strong><?php echo $reasonArr[0]; ?>:</strong> <?php echo $reasonArr[1]; ?></li>
	    		<?php	
	    	}
	    	?>
	    	</ul>
	    </div>
	    
	    <div class="option">
	    	<p>
	    		<a href="<?php echo $this->plugin->upgradeURL; ?>?utm_source=wordpress&utm_medium=link&utm_content=settings&utm_campaign=general" class="button button-primary" target="_blank"><?php _e('Upgrade Now', $this->plugin->name); ?></a>
	    	</p>
	    </div>
	</div>
	<?php
}
?>