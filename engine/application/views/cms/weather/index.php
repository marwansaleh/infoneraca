<div class="row">
    <div class="col-sm-12">
        <?php if ($this->session->flashdata('message')): ?>
        <?php echo create_alert_box($this->session->flashdata('message'),$this->session->flashdata('message_type')); ?>
        <?php endif; ?>
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">List of Weather Forecast</h3>
                <div class="box-tools">
                    <div class="input-group">
                        <input type="text" name="table_search" class="form-control input-sm pull-right" style="width: 250px;" placeholder="Search">
                        <div class="input-group-btn">
                            <button type="submit" class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                            <a class="btn btn-sm btn-primary" data-toggle="tooltip" title="Syncronize from API" href="javascript:syncWeather();"><i class="fa fa-cloud"></i></a>
                        </div>
                    </div>
                </div>
            </div><!-- /.box-header -->
            
            <div class="box-body table-responsive no-padding">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Date</th>
                            <th>City</th>
                            <th colspan="2">Summary</th>
                            <th>Check Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=1; foreach($items as $item): ?>
                        <tr>
                            <td><?php echo ($offset+$i++); ?>.</td>
                            <td><?php echo $item->last_checked_date; ?></td>
                            <td><?php echo $item->city_name; ?></td>
                            <td style="width: 70px;"><img src="<?php echo site_url($item->icon_local_url); ?>"></td>
                            <td><?php echo $item->api_result_summary; ?></td>
                            <td><?php echo date('d-M-Y H:i', $item->last_checked_time); ?></td>
                            <td class="text-center">
                                <a class="btn btn-xs btn-danger confirmation" data-toggle="tooltip" title="Delete" data-confirmation="Are your sure to delete this record ?" href="<?php echo site_url('cms/weather/delete?id='.$item->id.'&page='.$page); ?>"><i class="fa fa-minus-square"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div><!-- /.box-body -->
            <div class="box-footer clearfix">
                <!-- paging description -->
                <?php echo isset($pagination_description)? $pagination_description:''; ?>
                <!-- paging list bullets -->
                <?php echo isset($pagination)? $pagination:''; ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function syncWeather(){
        $.getJSON('<?php echo site_url('service/weather/sync'); ?>', function (data){
            alert('Done !. Please check console for the message');
            console.log('Message:' + data.message);
            console.log('Icon:'+ data.icon);
        });
    }
</script>