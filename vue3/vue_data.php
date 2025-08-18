<style>
  body{
    background-image: url("<?= base_url('assets/images/body.jpg') ?>");
    background-position: center;
    background-size: cover;
    background-repeat: no-repeat;
    height: 100vh;
    box-sizing: border-box;
    margin: 0;
    padding: 20px;
  }
</style>
<?= "<script>window.APP_CONFIG = " . json_encode(['apiSecretKey' => $api_key]) . ";</script>" ?>