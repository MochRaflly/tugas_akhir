<?php
if (!isset($error_message)) {
    $error_message = 'Terjadi kesalahan yang tidak diketahui.';
}
include 'header.php';
include 'navigation.php';
?>
<div class="error" style="margin: 40px auto; max-width: 600px; text-align: center;">
    <h3 style="color:#d32f2f;">Terjadi Kesalahan</h3>
    <p><?php echo htmlspecialchars($error_message); ?></p>
    <a href="javascript:history.back()" style="color:#1976d2; text-decoration:underline;">Kembali</a>
</div>
<?php include 'footer.php'; ?> 