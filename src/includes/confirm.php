<?php namespace s2clean;

if(!defined('WPINC'))
	exit('Do NOT access this file directly.');
$theme = theme(); // Theme instance.
?>
<?php do_action(__NAMESPACE__.'__before_confirm'); ?>

	<div id="confirm" class="modal fade fade-faster" aria-hidden="true" aria-labelledby="confirm-title" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">

				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">
						<i class="fa fa-times-circle"></i>
					</button>
					<h4 id="confirm-title" class="modal-title"></h4>
				</div>

				<div id="confirm-body" class="modal-body no-b-margin no-b-padding"></div>

				<div class="modal-footer" style="border-top:0;">
					<em class="pull-left opacity-fade">
						<?php echo __('Yes, or No?', $theme->text_domain); ?>
					</em>
					<button type="button" id="confirm-no-btn" class="btn btn-danger" data-dismiss="modal">
						&nbsp;<?php echo __('No', $theme->text_domain); ?>&nbsp;
					</button>
					<button type="button" id="confirm-yes-btn" class="btn btn-success" data-dismiss="modal">
						&nbsp;<?php echo __('Yes', $theme->text_domain); ?>&nbsp;
					</button>
				</div>

			</div>
		</div>
	</div>

<?php do_action(__NAMESPACE__.'__after_confirm'); ?>
