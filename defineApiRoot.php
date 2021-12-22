<script>
    // In my case, apache serves from /data/www/default, thus
    // the first 17 characters need to be skipped
    const folder = "<?= __DIR__ ?>";
    const apiRoot = `http://localhost${folder.endsWith('/') ? folder.substring(17, folder.length - 1) : folder.substring(17)}/api`;
</script>