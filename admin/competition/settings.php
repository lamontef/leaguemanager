<?php
if ( !current_user_can( 'manage_racketmanager' ) ) {
	echo '<p style="text-align: center;">'.__("You do not have sufficient permissions to access this page.").'</p>';

} else {
	$tab = 0;
	$competition = get_competition( intval($_GET['competition_id']) );
	if ( isset($_POST['updateSettings']) ) {
		check_admin_referer('racketmanager_manage-competition-options');

		$settings = (array)$_POST['settings'];

		$this->_editCompetition( intval($_POST['competition_id']), $_POST['competition_title'], $settings );
		$this->printMessage();

		$options = $racketmanager->options;
		$competition->reloadSettings();

		// Set active tab
		$tab = intval($_POST['active-tab']);
	}

	$forwin = $fordraw = $forloss = $forwin_overtime = $forloss_overtime = 0;
	// Manual point rule
	if ( is_array($competition->point_rule) ) {
		$forwin = $competition->point_rule['forwin'];
		$forwin_overtime = $competition->point_rule['forwin_overtime'];
		$fordraw = $competition->point_rule['fordraw'];
		$forloss = $competition->point_rule['forloss'];
		$forloss_overtime = $competition->point_rule['forloss_overtime'];
		$competition->point_rule = 'user';
	}
	?>

	<script type='text/javascript'>
	jQuery(function() {
		jQuery("#tabs.form").tabs({
			active: <?php echo $tab ?>
		});
	});
	</script>
	<div class="wrap">

		<form action="" method="post">
			<?php wp_nonce_field( 'racketmanager_manage-competition-options' ) ?>

			<div class="theme-settings-blocks form" id="tabs">
				<input type="hidden" class="active-tab" name="active-tab" value="<?php echo $tab ?>" ?>

				<ul id="tablist" style="display: none";>
					<li><h3><a href="#general"><?php _e( 'General', 'racketmanager' ) ?></a></h3></li>
					<li><h3><a href="#standings"><?php _e( 'Standings Table', 'racketmanager' ) ?></a></h3></li>
					<li><h3><a href="#advanced"><?php _e( 'Advanced', 'racketmanager' ) ?></a></h3></li>
					<li><h3><a href="#availability"><?php _e( 'Availability', 'racketmanager' ) ?></a></h3></li>
				</ul>

				<div id='general' class='settings-block-container'>
					<h2><?php _e( 'General', 'racketmanager' ) ?></h2>
					<div class="settings-block">
						<?php include('include/settings-general.php'); ?>
					</div>
				</div>

				<div id='standings' class='settings-block-container'>
					<h2><?php _e( 'Standings Table', 'racketmanager' ) ?></h2>
					<div class="settings-block">
						<?php include('include/settings-standings.php'); ?>
					</div>
				</div>

				<div id='advanced' class="settings-block-container">
					<h2><?php _e( 'Advanced', 'racketmanager' ) ?></h2>
					<div class="settings-block">
						<?php include('include/settings-advanced.php'); ?>
					</div>
				</div>

				<div id='availability' class="settings-block-container">
					<h2><?php _e( 'Availability', 'racketmanager' ) ?></h2>
					<div class="settings-block">
						<?php include('include/settings-availability.php'); ?>
					</div>
				</div>
			</div>
			<input type="hidden" name="competition_id" value="<?php echo $competition->id ?>" />
			<input type="submit" name="updateSettings" value="<?php _e( 'Save Settings', 'racketmanager' ) ?>" class="btn btn-primary" />
		</form>
	</div>

<?php } ?>
