<div id="escrocModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">{{__('dashboard.title_escroc')}}</h4>
      </div>
      <div class="modal-body">
        <div class="escroc-body">
            <?php echo __("dashboard.txt_escroc");?>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('dashboard.confirm') }}</button>
      </div>
    </div>

  </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $('#escrocModal').modal("show");
    });
</script>
