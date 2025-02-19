<form id="leagues-filter" method="post" action="">
	<?php wp_nonce_field( 'leagues-bulk' ) ?>

	<input type="hidden" name="competition_id" value="<?php echo $competition_id ?>" />
	<div class="tablenav">
		<!-- Bulk Actions -->
		<select name="action" size="1">
			<option value="-1" selected="selected"><?php _e('Bulk Actions') ?></option>
			<option value="delete"><?php _e('Delete')?></option>
		</select>
		<input type="submit" value="<?php _e('Apply'); ?>" name="doactionleague" id="doactionleague" class="btn btn-secondary action" />
	</div>

	<table class="widefat" summary="" title="RacketManager">
		<thead>
			<tr>
				<th scope="col" class="check-column"><input type="checkbox" onclick="Racketmanager.checkAll(document.getElementById('leagues-filter'));" /></th>
				<th scope="col" class="column-num">ID</th>
				<th scope="col"><?php _e( 'League', 'racketmanager' ) ?></th>
				<th scope="col" class="column-num"><?php _e( 'Teams', 'racketmanager' ) ?></th>
				<th scope="col" class="column-num"><?php _e( 'Matches', 'racketmanager' ) ?></th>
			</tr>
			<tbody id="the-list">
				<?php
				if ( $leagues = $competition->getLeagues( array('competition' => $competition_id)) ) {
					$class = '';
					foreach ( $leagues AS $league ) {
						$league = get_league($league);
						$class = ( 'alternate' == $class ) ? '' : 'alternate'; ?>
						<tr class="<?php echo $class ?>">
							<th scope="row" class="check-column"><input type="checkbox" value="<?php echo $league->id ?>" name="league[<?php echo $league->id ?>]" /></th>
							<td class="column-num"><?php echo $league->id ?></td>
							<td><a href="admin.php?page=racketmanager&amp;subpage=show-league&amp;league_id=<?php echo $league->id ?>"><?php echo $league->title ?></a></td>
							<td class="column-num"><?php echo $league->num_teams_total ?></td>
							<td class="column-num"><?php echo $league->num_matches_total ?></td>
						</tr>
					<?php } ?>
				<?php } ?>
			</tbody>
		</table>
	</form>

	<!-- Add New League -->
	<h3><?php _e( 'Add League', 'racketmanager' ) ?></h3>
	<form action="" method="post" class="form-control">
		<?php wp_nonce_field( 'racketmanager_add-league' ) ?>
		<input type="hidden" name="competition_id" value="<?php echo $competition_id ?>" />
		<input type="hidden" name="league_id" value="<?php echo $league_id ?>" />
		<div class="form-group">
			<div class="form-label">
				<label for="league_title"><?php _e( 'League', 'racketmanager' ) ?></label>
			</div>
			<div class="form-input">
				<input type="text" required="required" placeholder="<?php _e( 'Enter new league name', 'racketmanager') ?>"name="league_title" id="league_title" value="<?php echo $league_title ?>" size="30" />
			</div>
		</div>
		<div class="form-group">
			<div class="form-input">
				<input type="submit" name="addLeague" value="<?php if ( !$league_id ) _e( 'Add League', 'racketmanager' ); else _e( 'Update League', 'racketmanager' ); ?>" class="btn btn-primary" />
			</div>
		</div>
	</form>
