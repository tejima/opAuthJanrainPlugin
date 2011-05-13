<?php if ($form->getAuthMode() === 'Janrain'): ?>

<a class="rpxnow" onclick="return false;"
href="https://<?php echo Doctrine::getTable('SnsConfig')->get('zuniv.us.janrain_username') ?>.rpxnow.com/openid/v2/signin?token_url=<?php echo urlencode($form->getAuthAdapter()->getTokenURL()) ?>"> Sign In </a>

<script type="text/javascript">
  var rpxJsHost = (("https:" == document.location.protocol) ? "https://" : "http://static.");
  document.write(unescape("%3Cscript src='" + rpxJsHost +
"rpxnow.com/js/lib/rpx.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
  RPXNOW.overlay = true;
  RPXNOW.language_preference = 'en';
</script>

<?php else: ?>


<?php include_customizes($id, 'before') ?>

<div id="<?php echo $id ?>" class="loginForm">

<?php include_customizes($id, 'top') ?>

<form action="<?php echo $link_to ?>" method="post">
<table>
<?php echo $form ?>
<tr>
<td colspan="2">
<?php if ($form->getAuthAdapter()->getAuthConfig('help_login_error_action')) : ?>
<p class="password_query"><?php echo link_to(__('Can not access your account?'), $form->getAuthAdapter()->getAuthConfig('help_login_error_action')); ?></p>
<?php endif; ?>
<input type="submit" class="input_submit" value="<?php echo __('Login') ?>" />
</td>
</tr>
</table>
</form>

<?php if ($form->getAuthAdapter()->getAuthConfig('invite_mode') == 2
  && opToolkit::isEnabledRegistration('pc')
  && $form->getAuthAdapter()->getAuthConfig('self_invite_action')) : ?>
<?php echo link_to(__('Register'), $form->getAuthAdapter()->getAuthConfig('self_invite_action')) ?>
<?php endif; ?>

<?php include_customizes($id, 'bottom') ?>

</div>

<?php include_customizes($id, 'after') ?>


<?php endif; ?>
