jQuery(document).ready(function($) {
	// Checkbox toggles for text fields in Settings
	if ($('div.publishing-defaults').length > 0) {
		// Hide any text fields where no corresponding checkbox is ticked
		$('p input[type=checkbox]').each(function(i) {
			if (!$(this).attr('checked')) {
				$('p.update', $(this).parent().parent()).hide();
			}
		});
		
		// On checkbox change, show / hide corresponding text field
		$('p input[type=checkbox]').change(function() {
			if (!$(this).attr('checked')) {
				$('p.update', $(this).parent().parent()).hide();
			} else {
				$('p.update', $(this).parent().parent()).show();
			}	
		});
		
		// Dim any account images where no corresponding checkbox is ticked
		$('div.buffer-account input[type=checkbox]').each(function(i) {
			if (!$(this).attr('checked')) {
				$('img', $(this).parent()).fadeTo('fast', 0.4);
			}
		});
		
		// On checkbox change, show / dim corresponding account image
		$('div.buffer-account input[type=checkbox]').change(function() {
			if (!$(this).attr('checked')) {
				$('img', $(this).parent()).fadeTo('fast', 0.4);
			} else {
				$('img', $(this).parent()).fadeTo('fast', 1);
			}	
		});
	}
	
	if ($('div.wp-to-buffer-meta-box').length > 0) {
		// Hide default strings based on radio button
		if ($('div.wp-to-buffer-meta-box input[type=radio]:checked').val() == '1') {
			$('p.notes').show();	
		} else {
			$('p.notes').hide();	
		}
		
		// On radio change, show / hide default strings
		$('div.wp-to-buffer-meta-box input[type=radio]').change(function() {
			if ($(this).val() == '1') {
				$('p.notes').show();		
			} else {
				$('p.notes').hide();
			}
		});
	}
});