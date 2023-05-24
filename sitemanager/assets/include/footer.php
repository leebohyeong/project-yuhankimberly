<script>
    $('[data-btn="eventBtn"]').on('click', function () {
        const $this = $(this);
        const data = $this.data();
        EventListFn[data.fn](data, $this);
        return true;
    });
</script>

<iframe id="hiddenFrame" name="hiddenFrame" style="width:100%;height:200px;display:none;"></iframe>

<?php echo $DEBUGBAR_->render() ?>
