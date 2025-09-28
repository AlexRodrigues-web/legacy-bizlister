<?php
// Defaults p/ evitar avisos de variável indefinida
$Settings  = (isset($Settings) && is_array($Settings)) ? $Settings : [];
$FaceBook  = $FaceBook  ?? ($Settings['facebook']  ?? '');
$Twitter   = $Twitter   ?? ($Settings['twitter']   ?? '');
$Gplus     = $Gplus     ?? ($Settings['gplus']     ?? '');
$Pinterest = $Pinterest ?? ($Settings['pinterest'] ?? '');
$SiteTitle = $SiteTitle ?? ($Settings['title']     ?? '');

// helper de escape
function esc($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
?>

<footer class="main-footer">
  <div class="container">

    <div class="footer-center">
      <a href="advertise">Advertise</a>&nbsp; | &nbsp;
      <a href="privacy_policy">Privacy</a>&nbsp; | &nbsp;
      <a href="tos">Terms</a>&nbsp; | &nbsp;
      <a href="about_us">About Us</a>&nbsp; | &nbsp;
      <a href="contact_us">Contact Us</a>
    </div><!--footer-center-->

    <div class="footer-center">
      <?php if (!empty($FaceBook)): ?>
        <a class="footer-btns fa fa-facebook" href="<?= esc($FaceBook) ?>" target="_blank" rel="noopener"></a>
      <?php endif; ?>
      <?php if (!empty($Twitter)): ?>
        <a class="footer-btns fa fa-twitter" href="<?= esc($Twitter) ?>" target="_blank" rel="noopener"></a>
      <?php endif; ?>
      <?php if (!empty($Gplus)): ?>
        <a class="footer-btns fa fa-google-plus" href="<?= esc($Gplus) ?>" target="_blank" rel="noopener"></a>
      <?php endif; ?>
      <?php if (!empty($Pinterest)): ?>
        <a class="footer-btns fa fa-pinterest-p" href="<?= esc($Pinterest) ?>" target="_blank" rel="noopener"></a>
      <?php endif; ?>
    </div><!--footer-center-->

    <div class="footer-center copyright">
      &#169; <?= date("Y"); ?> <?= esc($SiteTitle); ?>
    </div><!--footer-center-->

  </div><!--container-->
</footer>

<?php if (!empty($Ad3)): ?>
  <div class="col-ad-mobile"><?= $Ad3; ?></div>
<?php endif; ?>

</div><!--wrap-->
</body>
</html>
