<?php foreach ($forms as $form) : ?>
<?php if ($form->getAuthMode() === 'Janrain'): ?>
<div class="row">
  <div class="gadget_header span12">OpenIDでログイン</div>
</div>
<div class="row">
<a class="rpxnow" onclick="return false;"
href="https://<?php echo Doctrine::getTable('SnsConfig')->get('zuniv.us.janrain_username') ?>.rpxnow.com/openid/v2/signin?token_url=<?php echo urlencode($form->getAuthAdapter()->getTokenURL()) ?>"> Sign In </a>
</div>
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

<div class="row">
  <div class="gadget_header span12">ログイン</div>
</div>
<?php echo form_tag(url_for(sprintf('@login'.'?%s=%s', opAuthForm::AUTH_MODE_FIELD_NAME, $form->getAuthMode()))) ?>

<?php $errors = array(); ?>
<?php if ($form->hasGlobalErrors()): ?>
<?php $errors[] = $form->renderGlobalErrors(); ?>
<?php endif; ?>
<?php if ($errors): ?>
<div class="row">
<div class="alert-message block-message error">
<a class="close" href="#">x</a>
<?php foreach ($errors as $error): ?>
<p><?php echo __($error) ?></p>
<?php endforeach; ?>
</div>
</div>
<?php endif; ?>

<div class="row">
<?php foreach ($form as $field): ?>
<?php if (!$field->isHidden()): ?>
  <div class="span12">
  <?php echo $field->renderLabel(); ?>
  </div>
  <?php if ($field->hasError()): ?>
  <div class="span12">
    <div class="clearfix error"><span class="label important"><?php echo __($field->getError()); ?></span>
    <?php if ($field->getWidget() instanceof sfWidgetFormInput && ("text" === $field->getWidget()->getOption('type') || "password" === $field->getWidget()->getOption('type'))): ?>
    <?php echo $field->render(array('class' => 'span12')); ?>
    <?php else: ?>
    <?php echo $field->render(); ?>
    <?php endif; ?>
    <span class="help-block"><?php echo $field->renderHelp(); ?></span>
    </div>
  </div>
  <?php else: ?>
  <div class="span12">
    <?php if ($field->getWidget() instanceof sfWidgetFormInput && ("text" === $field->getWidget()->getOption('type') || "password" === $field->getWidget()->getOption('type'))): ?>
    <?php echo $field->render(array('class' => 'span12')); ?>
    <?php else: ?>
    <?php echo $field->render(); ?>
    <?php endif; ?>
    <span class="help-block"><?php echo $field->renderHelp(); ?></span>
  </div>
  <?php endif; ?>
<?php endif; ?>
<?php endforeach; ?>
</div>

<div class="row">
<div class="span12">
<input type="submit" name="submit" value="<?php echo __('Login'); ?>" class="btn primary span12" />
<?php echo $form->renderHiddenFields(); ?>
</form>
</div>
<?php endif; ?>
<?php endforeach; ?>
