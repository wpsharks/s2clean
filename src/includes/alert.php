<?php namespace s2clean;

if(!defined('WPINC')) // MUST have WordPress.
	exit('Do NOT access this file directly: '.basename(__FILE__));
$theme = theme(); // Theme instance.
?>
<?php do_action(__NAMESPACE__.'__before_alert'); ?>

	<div id="alert" class="modal fade fade-faster" aria-hidden="true" aria-labelledby="alert-title" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">

				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">
						<i class="fa fa-times-circle"></i>
					</button>
					<h4 id="alert-title" class="modal-title"></h4>
				</div>

				<div id="alert-body" class="modal-body no-b-margin no-b-padding"></div>

				<div class="modal-footer" style="border-top:0;">
					<button type="button" id="alert-ok-btn" class="btn btn-default width-100" data-dismiss="modal">
						<?php echo __('OK', $theme->text_domain); ?>
					</button>
				</div>

			</div>
		</div>
	</div>

<?php do_action(__NAMESPACE__.'__after_alert'); ?>