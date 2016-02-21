<div class="row">
    <?php foreach ($widgets as $widget): ?>
    <?php if (widget_exists($widget)): $this->load->view('frontend/widgets/' . $widget); endif; ?>
    <?php endforeach; ?>
</div>