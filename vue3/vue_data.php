<style>
  body{
    background-image: url("<?= base_url('assets/images/body.jpg') ?>");
    background-position: center;
  }
</style>
<?= "<script>window.APP_CONFIG = " . json_encode(['apiSecretKey' => $api_key]) . ";</script>" ?>