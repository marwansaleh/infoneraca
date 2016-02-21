<div class="row">
    <div class="col-sm-12">
        <?php if ($this->session->flashdata('message')): ?>
        <?php echo create_alert_box($this->session->flashdata('message'),$this->session->flashdata('message_type')); ?>
        <?php endif; ?>
        <div class="box">
            <div class="box-header">
                <div class="row">
                    <div class="col-sm-3">
                        <h3 class="box-title">Posting Statistics</h3>
                    </div>
                    <div class="col-sm-9">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group form-group-sm">
                                    <select id="month" class="form-control selectpicker" data-live-search="true" data-size="8">
                                        <?php foreach ($months as $mindex=>$mname): ?>
                                        <option value="<?php echo $mindex; ?>" <?php echo $mindex==date('m')?'selected':''; ?>><?php echo $mname; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group form-group-sm">
                                    <select id="year" class="form-control">
                                        <?php foreach ($years as $year): ?>
                                        <option value="<?php echo $year; ?>" <?php echo $year==date('Y')?'selected':''; ?>><?php echo $year; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div><!-- /.box-header -->
            
            <div class="box-body table-responsive no-padding">
                <table class="table table-bordered" id="data-list">
                    <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>User Name</th>
                            <th>#Articles</th>
                            <th>#Published</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div><!-- /.box-body -->
        </div>
    </div>
</div>
<div class="modal fade" tabindex="-1" role="dialog" id="MyModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Modal title</h4>
            </div>
            <div class="modal-body">
                <table id="data-detail" class="table table-bordered">
                    <thead>
                        <tr><th>Date</th><th>Title</th><th>Category</th><th>Published</th></tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script type="text/javascript">
    $(document).ready (function (){
        StatManager.init();
        StatManager.load();
        
        $('#month').on('change', function(){
            StatManager.setMonth($(this).val());
            StatManager.load();
        });
        $('#year').on('change', function(){
            StatManager.setYear($(this).val());
            StatManager.load();
        });
    });
    var StatManager = {
        month: 1,
        year: 1,
        init: function (){
            //get month and year
            this.setMonth($('select#month').val());
            this.setYear($('select#year').val());
        },
        getMonth: function(){
            return this.month;
        },
        setMonth: function(month){
            this.month = parseInt(month);
        },
        getYear: function(){
            return this.year;
        },
        setYear: function(year){
            this.year = parseInt(year);
        },
        load: function (){
            var _this = this;
            $.getJSON("<?php echo site_url('service/user/posts'); ?>",{month:_this.getMonth(),year:_this.getYear()}, function(data){
                $('#data-list tbody').empty();
                
                for (var i in data){
                    var s = '<tr>';
                        s+= '<td>'+(parseInt(i)+1)+'.</td>';
                        s+= '<td>'+data[i].name+'</td>';
                        s+= '<td>'+data[i].articles+'</td>';
                        s+= '<td>'+data[i].published+'</td>';
                        s+= '<td class="text-center"><button class="btn btn-default btn-xs" onclick="showDetail('+data[i].userid+','+_this.getMonth()+','+_this.getYear()+')"><span class="fa fa-info"></span> ShowDetail</button></td>';
                    s+= '</tr>';

                    $('#data-list').append(s);
                }
            });
        },
        showDetail: function (id,month,year){
            var _this = this;
            $('#MyModal tbody').empty();
            $('#MyModal').modal('show');
            $('#MyModal h4').html('Loading....');
            
            $.getJSON("<?php echo site_url('service/user/postdetail'); ?>",{user:id,month:month,year:year}, function(data){
                $('#MyModal h4').html('Statistic of '+data.user.name+' ('+data.user.articles+' articles)');
                
                for (var i in data.articles){
                    var s = '<tr>';
                        s+= '<td>'+data.articles[i].date+'</td>';
                        s+= '<td>'+(_this._detailLink(data.articles[i].title, data.articles[i].url))+'</td>';
                        s+= '<td>'+data.articles[i].category+'</td>';
                        s+= '<td>'+data.articles[i].published+'</td>';
                    s+= '</tr>';

                    $('#data-detail tbody').append(s);
                }
            });
        },
        _detailLink: function(title,url){
            var url = '<a href="'+url+'" target="blank">'+title+'</a>';
            
            return url;
        }
    };
    
    function showDetail(id,month,year){
        StatManager.showDetail(id,month,year);
    }
</script>