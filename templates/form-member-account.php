<div id="member-account-form" class="login-form-container widecolumn">
    <h3><?php _e( 'Member Account', 'racketmanager' ); ?></h3>

    <?php if ( isset( $user_data['message'] ) )  {
        if ( isset($user_data['error']) ) {
            $class = 'message-error';
        } else {
            $class = 'message-success';
        } ?>
            <p id="profile-message" class="<?php echo $class ?>"><?php echo $user_data['message']; ?></p>
    <?php } ?>

    <form name="memberaccountform" id="memberaccountform" action="<?php echo site_url( 'member-account' ); ?>" method="post" autocomplete="off">
        <?php wp_nonce_field( 'member_account_nonce', 'member_account_nonce_field' ); ?>

        <fieldset>
            <h4><?php _e( 'Personal Information', 'racketmanager' ) ?></h4>
            <div class="form-group <?php if (isset($user_data['user_name_error'])) echo 'field_error' ?>">
                <label for="username"><?php _e( 'Username', 'racketmanager' ) ?></label>
                <div class="input">
                    <input type="email" required="required" placeholder="<?php _e( 'Email Address', 'racketmanager' ) ?>" name="username" id="username" class="input" value="<?php echo $user_data['user_name'] ?>" <?php if (isset($user_data['user_name']) && $user_data['user_name'] != '' ) echo 'readonly' ?> />
                    <?php if (isset($user_data['user_name_error'])) echo '<span class="form-error">'.$user_data["user_name_error"].'</span>' ?>
                </div>
            </div>
            <div class="form-group <?php if (isset($user_data['first_name_error'])) echo 'field_error' ?>">
                <label for="firstname"><?php _e( 'First Name', 'racketmanager' ) ?></label>
                <div class="input">
                    <input type="text" autocomplete='given-name' placeholder="<?php _e( 'First Name', 'racketmanager' ) ?>" name="firstname" id="firstname" class="input" value="<?php echo $user_data['first_name'] ?>" />
                <?php if (isset($user_data['first_name_error'])) echo '<span class="form-error">'.$user_data["first_name_error"].'</span>' ?>
                </div>
            </div>
            <div class="form-group <?php if (isset($user_data['last_name_error'])) echo 'field_error' ?>">
                <label for="lastname"><?php _e( 'Last Name', 'racketmanager' ) ?></label>
                <div class="input">
                    <input type="text" autocomplete='family-name' placeholder="<?php _e( 'Last Name', 'racketmanager' ) ?>" name="lastname" id="lastname" class="input" value="<?php echo $user_data['last_name'] ?>" />
                </div>
                <?php if (isset($user_data['last_name_error'])) echo '<span class="form-error">'.$user_data["last_name_error"].'</span>' ?>
            </div>
            <div class="form-group <?php if (isset($user_data['contactno_error'])) echo 'field_error' ?>">
                <label for="contactno"><?php _e( 'Telephone Number', 'racketmanager' ) ?></label>
                <div class="input">
                    <input type="tel" autocomplete='tel' placeholder="<?php _e( 'Telephone Number', 'racketmanager' ) ?>" name="contactno" id="contactno" class="input" value="<?php echo $user_data['contactno'] ?>" />
                </div>
                <?php if (isset($user_data['contactno_error'])) echo '<span class="form-error">'.$user_data["contactno_error"].'</span>' ?>
            </div>
            <div class="form-group <?php if (isset($user_data['gender_error'])) echo 'field_error' ?>">
                <label class="label-radio"><?php _e( 'Gender', 'racketmanager' ) ?></label>
                <div class="form-check">
                    <input type="radio" class="form-check-input" required="required" id="genderMale" name="gender" value="M"<?php echo ($user_data['gender'] == 'M') ? 'checked' : '' ?> />
                    <label for="genderMale" class="form-check-label"><?php _e('Male', 'racketmanager') ?></label>
                </div>
                <div class="form-check">
                    <input type="radio" class="form-check-input" id="genderFemale" name="gender" value="F" <?php echo ($user_data['gender'] == 'F') ? 'checked' : '' ?> />
                    <label for="genderFemale" class="form-check-label"><?php _e('Female', 'racketmanager') ?></label>
                </div>
                <?php if (isset($user_data['gender_error'])) echo '<span class="form-error">'.$user_data["gender_error"].'</span>' ?>
            </div>
            <div class="form-group <?php if (isset($user_data['btm_error'])) echo 'field_error' ?>">
                <label for="btm"><?php _e( 'BTM Number', 'racketmanager' ) ?></label>
                <div class="input">
                    <input type="tel" placeholder="<?php _e( 'BTM Number', 'racketmanager' ) ?>" name="btm" id="btm" class="input" value="<?php echo $user_data['btm'] ?>" />
                </div>
                <?php if (isset($user_data['btm_error'])) echo '<span class="form-error">'.$user_data["btm_error"].'</span>' ?>
            </div>
       </fieldset>

        <fieldset>
            <h4><?php _e( 'Change Password', 'racketmanager' ) ?></h4>
            <p><?php _e('When both password fields are left empty, your password will not change', 'racketmanager'); ?></p>
            <div class="form-group <?php if (isset($user_data['password_error'])) echo 'field_error' ?>">
                <label for="password"><?php _e( 'Password', 'racketmanager' ) ?></label>
                <div class="input">
                    <input type="password" placeholder="<?php _e( 'Password', 'racketmanager' ) ?>" name="password" id="password" class="password" size="20" value="" autocomplete="off" />
                    <i class="passwordShow racketmanager-svg-icon">
                        <?php racketmanager_the_svg('icon-eye') ?>
                    </i>
                </div>
                <?php if (isset($user_data['password_error'])) echo '<span class="form-error">'.$user_data["password_error"].'</span>' ?>
            </div>
            <div class="form-group <?php if (isset($user_data['rePassword_error'])) echo 'field_error' ?>">
                <label for="rePassword"><?php _e( 'Confirm password', 'racketmanager' ) ?></label>
                <div class="input">
                    <input type="password" placeholder="<?php _e( 'Re-enter password', 'racketmanager' ) ?>" name="rePassword" id="rePassword" class="password" size="20" value="" autocomplete="off" />
                    <i class="passwordShow racketmanager-svg-icon">
                        <?php racketmanager_the_svg('icon-eye') ?>
                    </i>
                </div>
                <?php if (isset($user_data['rePassword_error'])) echo '<span class="form-error">'.$user_data["rePassword_error"].'</span>' ?>
            </div>
            <div class="form-group">
              <span id="password-strength"></span>
            </div>
        </fieldset>

        <p class="memberaccount-submit">
            <input type="submit" name="submit" id="memberaccount-button"
                   class="button" value="<?php _e( 'Update Details', 'racketmanager' ); ?>" />
            <input name="action" type="hidden" id="action" value="update-user" />
        </p>
    </form>
</div>
