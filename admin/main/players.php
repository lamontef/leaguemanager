<!-- Add Player -->
<form action="" method="post" class="form-control">
	<?php wp_nonce_field( 'racketmanager_add-player' ) ?>
	<div class="form-group">
		<label for="firstname"><?php _e( 'First Name', 'racketmanager' ) ?></label>
		<div class="input">
			<input required="required" placeholder="<?php _e( 'Enter first name', 'racketmanager') ?>" type="text" name="firstname" id="firstname" value="" size="30" />
		</div>
	</div>
	<div class="form-group">
		<label for="surname"><?php _e( 'Surname', 'racketmanager' ) ?></label>
		<div class="input">
			<input required="required"  placeholder="<?php _e( 'Enter surname', 'racketmanager') ?>" type="text" name="surname" id="surname" value="" size="30" />
		</div>
	</div>
	<div class="form-group">
		<label><?php _e('Gender', 'racketmanager') ?></label>
		<div class="form-check">
			<input type="radio" required="required" name="gender" id="genderMale" value="M" /><label for "genderMale"><?php _e('Male', 'racketmanager') ?></label>
		</div>
		<div class="form-check">
			<input type="radio" required="required" name="gender" id="genderFemale" value="F" /><label for "genderFemale"><?php _e('Female', 'racketmanager') ?></label>
		</div>
	</div>
	<div class="form-group">
		<label for="btm"><?php _e('BTM', 'racketmanager') ?></label>
		<div class="input">
			<input type="number"  placeholder="<?php _e( 'Enter BTM number', 'racketmanager') ?>" name="btm" id="gender" size="11" />
		</div>
	</div>
	<input type="hidden" name="addPlayer" value="player" />
	<input type="submit" name="addPlayer" value="<?php _e( 'Add Player','racketmanager' ) ?>" class="btn btn-primary" />

</form>

<form id="player-filter" method="post" action="">
	<?php wp_nonce_field( 'player-bulk' ) ?>

	<div class="tablenav">
		<!-- Bulk Actions -->
		<select name="action" size="1">
			<option value="-1" selected="selected"><?php _e('Bulk Actions') ?></option>
			<option value="delete"><?php _e('Delete')?></option>
		</select>
		<input type="submit" value="<?php _e('Apply'); ?>" name="doPlayerDel" id="dorPlayerDel" class="btn btn-secondary action" />
	</div>

	<div class="container">
		<div class="row table-header">
			<div class="col-1 check-column"><input type="checkbox" onclick="Racketmanager.checkAll(document.getElementById('player-filter'));" /></div>
			<div class="col-1 column-num">ID</div>
			<div class="col-3"><?php _e( 'Name', 'racketmanager' ) ?></div>
			<div class="col-1"><?php _e( 'Gender', 'racketmanager' ) ?></div>
			<div class="col-1"><?php _e( 'BTM', 'racketmanager' ) ?></div>
			<div class="col-1"><?php _e( 'Created', 'racketmanager') ?></div>
			<div class="col-1"><?php _e( 'Removed', 'racketmanager') ?></div>
		</div>
		<?php if ( $players = $racketmanager->getPlayers( array() ) ) {
			$class = '';
			foreach ( $players AS $player ) {
				$class = ( 'alternate' == $class ) ? '' : 'alternate'; ?>
				<div class="row table-row <?php echo $class ?>">
					<div class="col-1 check-column">
						<?php if ( $player->removed_date == '' ) { ?>
							<input type="checkbox" value="<?php echo $player->id ?>" name="player[<?php echo $player->id ?>]" />
						<?php } ?>
					</div>
					<div class="col-1 column-num"><?php echo $player->id ?></div>
					<div class="col-3"><?php echo $player->fullname ?></div>
					<div class="col-1"><?php echo $player->gender ?></div>
					<div class="col-1"><?php echo $player->btm ?></div>
					<div class="col-1"><?php echo substr($player->created_date,0,10) ?></div>
					<div class="col-1"><?php if ( isset($player->removed_date) ) { echo $player->removed_date; } ?></div>
				</div>
			<?php } ?>
		<?php } ?>
	</div>
</form>
