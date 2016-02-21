<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" type="image/png" href="<?php echo site_url('assets/img/one.png'); ?>"/>
        <!-- Meta tags -->
        <title>Application Log</title>

        <!-- Bootstrap CSS -->    
        <link href="<?php echo site_url(config_item('path_lib').'bootstrap/css/bootstrap.min.css'); ?>" rel="stylesheet">
        <!-- bootstrap theme -->
        <link href="<?php echo site_url(config_item('path_lib').'bootstrap/css/bootstrap-theme.min.css'); ?>" rel="stylesheet">
        <!--external css-->
        <link href="<?php echo site_url(config_item('path_lib').'font-awesome-4.1.0/css/font-awesome.min.css'); ?>" rel="stylesheet" />    
      
        <!-- Custom styles -->
        <link href="<?php echo site_url(config_item('path_assets').'css/log.css'); ?>" rel="stylesheet">
        <script src="<?php echo site_url(config_item('path_lib').'jquery/jquery-1.11.2.min.js'); ?>"></script>
        
        <!-- HTML5 shim and Respond.js IE8 support of HTML5 -->
        <!--[if lt IE 9]>
          <script src="<?php echo site_url(config_item('path_lib').'html5shiv/html5shiv.min.js'); ?>"></script>
          <script src="<?php echo site_url(config_item('path_lib').'respondjs/respond.min.js'); ?>"></script>
          <script src="<?php echo site_url(config_item('path_lib').'lte-ie7/lte-ie7.js'); ?>"></script>
        <![endif]-->
        
    </head>
    <body>
        <div id="main" class="container-fluid">
            <h1 class="page-header">Log Environment: <?php echo strtoupper(ENVIRONMENT); ?></h1>
            <div class="row">
                <div class="col-sm-2 col-sm-offset-3">
                    <div class="input-group input-group-sm">
                        <input type="number" class="form-control" id="lines" name="lines" step="1" min="5" value="10" title="Set number of rows">
                        <div class="input-group-btn">
                            <button type="button" class="btn btn-success" id="btn-setlines"><span class="fa fa-adjust"></span> #Lines</button>
                        </div>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="input-group input-group-sm">
                        <input type="number" class="form-control" id="interval" name="interval" step="1000" min="1000" value="60000" title="Set number of miliseconds to refresh">
                        <div class="input-group-btn">
                            <button type="button" class="btn btn-warning" id="btn-setinterval"><span class="fa fa-adjust"></span> #Interval</button>
                        </div>
                    </div>
                </div>
                <div class="col-sm-2">
                    <button type="button" class="btn btn-primary btn-sm" id="btn-refresh"><span class="fa fa-refresh"></span> Refresh</button>
                </div>
            </div>
            <hr>    
            <div class="row">
                <div class="col-sm-12">
                    <table class="table table-striped table-condensed table-log">
                        <thead>
                            <tr>
                                <th>Datetime</th>
                                <th>Cookie ID</th>
                                <th>IP Address</th>
                                <th>UserAgent</th>
                                <th>Event Description</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <script type="text/javascript">
            $(document).ready(function (){
                LogManager.setLines($('#lines').val());
                LogManager.setInterval($('#interval').val());
                LogManager.init();
                
                $('#btn-refresh').on('click', function (){
                    LogManager.refresh();
                });
                $('#btn-setlines').on('click', function (){
                    LogManager.setLines($('#lines').val());
                });
                $('#btn-setinterval').on('click', function (){
                    LogManager.setInterval($('#interval').val());
                });
            });
            var LogManager = {
                inProccess: false,
                numLine: 10,
                repeatSecs: 60000,
                repeatId: null,
                init: function (){
                    this.loadLines();
                },
                setLines: function (num){
                    this.numLine = parseInt(num); 
                },
                setInterval: function (num){
                    this.repeatSecs = parseInt(num);
                },
                loadLines: function (){
                    var _this = this;
                    if (_this.inProccess){
                        return;
                    }
                    _this.inProccess = true;
                    $('#main table tbody').empty();
                    $.getJSON("<?php echo site_url('service/log/index'); ?>",{lines:_this.numLine},function(data){
                        
                        _this.inProccess = false;
                        
                        for (var i in data){
                            var s = '<tr>';
                                s+= '<td class="datetime">'+data[i].datetime+'</td>'
                                s+= '<td class="cookie-id">'+data[i].cookie_id+'</td>';
                                s+= '<td class="ip-address">'+data[i].ip_address+'</td>';
                                s+= '<td class="agent-string">'+data[i].agent_string+'</td>';
                                s+= '<td class="event-description">'+data[i].event_description+'</td>';
                            s+='</tr>';
                            
                            $('#main table tbody').append(s);
                        }
                        
                        //repeat again loading logs
                        _this.repeatId = setTimeout(function (){
                            _this.loadLines();
                        }, _this.repeatSecs);
                    });
                },
                refresh: function(){
                    if (this.repeatId){
                        clearTimeout(this.repeatId);
                    }
                    this.loadLines();
                }
            };
        </script>
        
        <!-- bootstrap -->
        <script src="<?php echo site_url(config_item('path_lib').'bootstrap/js/bootstrap.min.js'); ?>"></script>
        <!-- nice scroll -->
        <script src="<?php echo site_url(config_item('path_lib').'scrollTo/jquery.scrollTo.min.js'); ?>"></script>
        <script src="<?php echo site_url(config_item('path_lib').'nicescroll/jquery.nicescroll.min.js'); ?>" type="text/javascript"></script>
    </body>
</html>