<?php defined('_VR360_EXEC') or die; ?>

<?php require_once __DIR__ . '/default_nav.php'; ?>
<?php require_once __DIR__ . '/default_tours.php'; ?>

<!-- General modal -->
<div
		class="modal fade"
		id="vrTour"
		tabindex="-1"
		role="dialog"
		aria-labelledby="vrTourLabel"
		data-backdrop="static"
		data-keyboard="false"
>
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<!-- Header -->
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i
								class="fas fa-window-close"></i></span>
				</button>
				<h4 class="modal-title" id="myModalLabel">
					<i class="fa fa-plus-square" aria-hidden="true"></i> <?php echo \Joomla\Language\Text::_('TOURS_LABEL_NEW_TOUR'); ?>
				</h4>
			</div>
			<!-- Body -->
			<div class="modal-body">
				<div class="container-fluid"></div>
			</div>
			<!-- Footer -->
			<div class="modal-footer">
				<button
						type="button"
						class="btn btn-danger"
						data-dismiss="modal"
						id="modal-close"
						onclick="vrAdmin.reload()"
				>
					<i class="fas fa-window-close"></i> <?php echo \Joomla\Language\Text::_('GENERAL_LABEL_CLOSE'); ?>
				</button>
			</div>
		</div>
	</div>
</div>
