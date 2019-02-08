<?php

// Split the name on whitespaces into an array
$firstname = explode( " ", $_SESSION['name'] );
// Get the first name of the user
$firstname = $firstname[0];

$title = $firstname . TITLE_DELIMITER . TITLE;

include $_SERVER['DOCUMENT_ROOT'] . '/includes/head.php';

?>

			<main>
		
				<h1>Account</h1>

				<h2 id="name"><?php echo $_SESSION['name']; ?></h2>

				<form id="form_modify_account" class="accordian">

					<h3 class="accordian_trigger">Change photo</h3>
					<div class="accordian_box">
						<formset>
							<legend>Click to upload your photo.</legend>
							<label id="modify_photo_container" for="modify_photo">
								<img class="user_photo" id="modify_photo_img" src="/img/user_photos/<?php echo $_SESSION['user_id'] ?>.jpg" alt="User photo" onerror="this.src='/img/avatar.png'">
							</label>
							<input type="file" id="modify_photo" name="modify_photo" accept=".jpg">
						</formset>
					</div>
					
					<!--
					<h3 class="accordian_trigger">Change email</h3>
					<div class="accordian_box">
						<formset>
							<legend>Change email</legend>
							<input type="email" id="modify_email" name="modify_email" placeholder="Email">
						</formset>
					</div>

					<h3 class="accordian_trigger">Change password</h3>
					<div class="accordian_box">
						<formset>
							<legend>Change password</legend>
							<input type="password" id="modify_password" name="modify_password" autocomplete="off" placeholder="Password">
							<input type="password" id="modify_password_cnfrm" name="modify_password_cnfrm" autocomplete="off" placeholder="Confirm password">
						</formset>
					</div>

					<h3 class="accordian_trigger">Change privacy</h3>
					<div class="accordian_box">
						<formset id="modify_privacy">
							<legend>Change privacy</legend>
							<label><input type="radio" id="modify_private" name="modify_privacy" value="private">Private</label>
							<label><input type="radio" id="modify_public" name="modify_privacy" value="public">Public</label>
						</formset>
					</div>

					<h3 class="accordian_trigger">Delete account</h3>
					<div class="accordian_box">
						<formset>
							<legend>Delete account</legend>
							<input type="password" id="modify_delete_password" name="modify_delete_password" autocomplete="off" placeholder="Password">
							<input type="checkbox" id="modify_delete_cnfrm" name="modify_delete_cnfrm">
							<label id="modify_delete_label" for="modify_delete_cnfrm">I understand that I'm permanently deleting my Runners&apos; High account.</label>
						</formset>
					</div>

					<button type="button" id="modify_account_update">Update</button> -->

				</form>

			</main>
			
<?php

include $_SERVER['DOCUMENT_ROOT'] . '/includes/end_of_page.php'; ?>