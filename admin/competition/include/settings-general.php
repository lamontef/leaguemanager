<div class="form-group">
  <div class="form-label">
    <label for="competition_title"><?php _e( 'Title', 'racketmanager' ) ?></label>
  </div>
  <div class="form-input">
    <input type="text" name="competition_title" id="competition_title" value="<?php echo $competition->name ?>" size="30" />
  </div>
</div>
<div class="form-group">
  <div class="form-label">
    <label for="sport"><?php _e( 'Sport', 'racketmanager' ) ?></label>
  </div>
  <div class="form-hint">
    <?php printf( __( "Check the <a href='%s'>Documentation</a> for details", 'racketmanager'), admin_url() . 'admin.php?page=racketmanager-doc' ) ?>
  </div>
  <div class="form-input">
    <select size="1" name="settings[sport]" id="sport">
      <?php foreach ( $racketmanager->getLeagueTypes() AS $id => $title ) { ?>
        <option value="<?php echo $id ?>"<?php selected( $id, $competition->sport ) ?>><?php echo $title ?></option>
      <?php } ?>
    </select>
  </div>
</div>
<div class="form-group">
  <div class="form-label">
    <label for="point_rule"><?php _e( 'Point Rule', 'racketmanager' ) ?></label>
  </div>
  <div class="form-hint">
    <?php printf( __("For details on point rules see the <a href='%s'>Documentation</a>", 'racketmanager'), admin_url() . 'admin.php?page=racketmanager-doc' ) ?>
  </div>
  <div class="form-input">
    <select size="1" name="settings[point_rule]" id="point_rule">
      <?php foreach ( $this->getPointRules() AS $id => $point_rule ) { ?>
        <option value="<?php echo $id ?>"<?php selected( $id, $competition->point_rule ) ?>><?php echo $point_rule ?></option>
      <?php } ?>
    </select>
  </div>
</div>
<div class="form-group">
  <div class="form-label">
    <label for="point_format"><?php _e( 'Point Format', 'racketmanager' ) ?></label>
  </div>
  <div class="form-hint">
    <?php _e( 'Point formats for primary and seconday points (e.g. Goals)', 'racketmanager' ) ?>
  </div>
  <div class="form-input">
    <select size="1" name="settings[point_format]" id="point_format" >
      <?php foreach ( $this->getPointFormats() AS $id => $format ) { ?>
        <option value="<?php echo $id ?>"<?php selected ( $id, $competition->point_format ) ?>><?php echo $format ?></option>
      <?php } ?>
    </select>
    <select size="1" name="settings[point_format2]" id="point_format2" >
      <?php foreach ( $this->getPointFormats() AS $id => $format ) { ?>
        <option value="<?php echo $id ?>"<?php selected ( $id, $competition->point_format2 ); ?>><?php echo $format ?></option>
      <?php } ?>
    </select>
  </div>
</div>
<div class="form-group">
  <div class="form-label">
    <label for="team_ranking"><?php _e( 'Team Ranking', 'racketmanager' ) ?></label>
  </div>
  <div class="form-input">
    <select size="1" name="settings[team_ranking]" id="team_ranking" >
      <option value="auto"<?php selected( 'auto', $competition->team_ranking  ) ?>><?php _e( 'Automatic', 'racketmanager' ) ?></option>
      <option value="manual"<?php selected( 'manual', $competition->team_ranking  ) ?>><?php _e( 'Manual', 'racketmanager' ) ?></option>
    </select>
  </div>
</div>
<div class="form-group">
  <div class="form-label">
    <label for="mode"><?php _e( 'Mode', 'racketmanager' ) ?></label>
  </div>
  <div class="form-input">
    <select size="1" name="settings[mode]" id="mode">
      <?php foreach ( $this->getModes() AS $id => $mode ) { ?>
        <option value="<?php echo $id ?>"<?php selected( $id, $competition->mode ) ?>><?php echo $mode ?></option>
      <?php } ?>
    </select>
  </div>
</div>
<div class="form-group">
  <div class="form-label">
    <label for="entryType"><?php _e( 'Entry Type', 'racketmanager' ) ?></label>
  </div>
  <div class="form-input">
    <select size="1" name="settings[entryType]" id="entryType">
      <?php foreach ( $this->getentryTypes() AS $id => $entryType )  { ?>
        <option value="<?php echo $id ?>"<?php selected( $id, isset($competition->entryType) ? $competition->entryType : '' ) ?>><?php echo $entryType ?></option>
      <?php } ?>
    </select>
  </div>
</div>
<div class="form-group">
  <div class="form-label">
    <label for="default_start_time"><?php _e( 'Default Match Start Time', 'racketmanager' ) ?></label>
  </div>
  <div class="form-input">
    <select size="1" name="settings[default_match_start_time][hour]">
      <?php for ( $hour = 0; $hour <= 23; $hour++ ) { ?>
        <option value="<?php echo str_pad($hour, 2, 0, STR_PAD_LEFT) ?>"<?php selected( $hour, $competition->default_match_start_time['hour'] ) ?>><?php echo str_pad($hour, 2, 0, STR_PAD_LEFT) ?></option>
      <?php } ?>
    </select>
    <select size="1" name="settings[default_match_start_time][minutes]">
      <?php for ( $minute = 0; $minute <= 60; $minute++ ) { ?>
        <?php if ( 0 == $minute % 5 && 60 != $minute ) { ?>
          <option value="<?php  echo str_pad($minute, 2, 0, STR_PAD_LEFT) ?>"<?php selected( $minute, $competition->default_match_start_time['minutes'] ) ?>><?php echo str_pad($minute, 2, 0, STR_PAD_LEFT) ?></option>
        <?php } ?>
      <?php } ?>
    </select>
  </div>
</div>
<div class="form-group">
  <div class="form-label">
    <label for="num_matches_per_page"><?php _e( 'Matches per page', 'racketmanager' ) ?></label>
  </div>
  <div class="form-hint">
    <?php _e( 'Number of matches to show per page', 'racketmanager' ) ?>
  </div>
  <div class="form-input">
    <input type="number" step="1" min="0" class="small-text" name="settings[num_matches_per_page]" id="num_matches_per_page" value="<?php echo $competition->num_matches_per_page ?>" size="2" />
  </div>
</div>
