var SocialMedia = {
    FB_APP_ID: '1667512626834805',
    FB_Scope: 'email,public_profile,publish_actions,user_posts',
    webLoginDlgId: 'login-dialog',
    currentUrl: window.location.href,
    init: function (){
        if (document.getElementById('fbscope')){
            this.setFB_Scope(document.getElementById('fbscope').value);
        }
        this._fb_init();
    },
    _fb_init: function (){
        var _this = this;
        window.fbAsyncInit = function() {
            FB.init({
              appId      : _this.FB_APP_ID,
              cookie     : true,
              xfbml      : true,
              version    : 'v2.5'
            });
        };

        (function(d, s, id) {
          var js, fjs = d.getElementsByTagName(s)[0];
          if (d.getElementById(id)) return;
          js = d.createElement(s); js.id = id;
          js.src = "//connect.facebook.net/id_ID/sdk.js#xfbml=1&version=v2.5";
          fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    },
    setFB_ID: function (id){
        this.FB_APP_ID = id;
    },
    setFB_Scope: function (scope){
        this.FB_Scope = scope;
    },
    fbLogin: function (response){
        var _this = this;
        FB.login( function (response){
            if (response.status === 'connected') {
                // Logged into your app and Facebook.
                //console.log('User is loggedin into facebook and app. Store user data');
                _this.fbGetMe(true);
            } else {
                // The person is not logged into Facebook, so we're not sure if
                // they are logged into this app or not.
                //console.log('Not logged into facebook or app. Should redirect to normal login page');
                _this.fbLoginFailed();
            }
        }, {scope: _this.FB_Scope});
    },
    fbGetLoginStatus: function (){
        var _this = this;
        FB.getLoginStatus(function(response) {
            _this.fbStatusChangeCallback(response);
        });
    },
    fbStatusChangeCallback: function(response){
        var _this = this;
        //console.log('statusChangeCallback');
        //console.log(response);
        // The response object is returned with a status field that lets the
        // app know the current login status of the person.
        // Full docs on the response object can be found in the documentation
        // for FB.getLoginStatus().
        if (response.status === 'connected') {
            // Logged into your app and Facebook.
            _this.closeLoginOptDialog();
            _this.fbGetMe(true);
        } else {
            // The person is not logged into Facebook, so we're not sure if
            // they are logged into this app or not.
            //document.getElementById('status').innerHTML = 'Please log into Facebook.';
            //console.log('Please log into Facebook. Opening FB login dialog');
            _this.closeLoginOptDialog();
            //console.log('Close internal login dialog options');
            _this.fbLogin(response);
        }
    },
    fbGetMe: function (save){
        var _this = this;
        //console.log('Welcome!  Fetching your information.... ');
        FB.api('/me?fields=id,name,email,picture{url}', function(response) {
            //console.log('Successful login for: ' + response.name);
            if (save === true){
                _this.fbRedirectSaveUser(response);
            }
        });
    },
    fbRedirectSaveUser: function (response){
        var _this = this;
        //console.log(JSON.stringify(response));
        var loc = window.location;
        var base_url = loc.protocol + '//'+loc.host;
        var service_url = base_url+'/service/user/socmed';
        $.post(service_url, {
            app: 'facebook',
            id: response.id,
            name: response.name,
            email: response.email,
            picture: response.picture.data.url
        },function(result){
            if (result.status==true){
                var redirect_login = base_url+'/auth/loginext/'+result.user.id+'?redirect='+encodeURIComponent(_this.currentUrl);
                window.location.replace(redirect_login);
            }
        }, 'json');
    },
    fbLoginFailed: function (){
        console.log('Login failed');
    },
    fbShare: function (articleId,link){
        var _this = this;
        FB.ui({
          method: 'share',
          href: link
        }, function(response){
            //console.log(JSON.stringify(response));
            if (response && response.post_id){
                var serviceUrl = _this.serviceUrl('article/shares/'+articleId);
                $.post(serviceUrl,{post_id:response.post_id,post_app:'facebook'},function(result){
                    //console.log(JSON.stringify(result));
                });
            }
        }); 
    },
    fbShareCount: function (url,callback){
        var _this = this;
        FB.api('/'+url+'?fields=share', function(response){
            callback(response);
        });
    },
    twShare: function(url,text){
        var tw_window = window.open('https://twitter.com/intent/tweet?url='+url+'&text='+text,'Twitter-Web-Intent');
        tw_window.focus();
    },
    closeLoginOptDialog: function(){
        var _this = this;
        $('#'+_this.webLoginDlgId).modal('hide');
    },
    serviceUrl: function(service){
        var serviceBase = this._getServiceBaseUrl();
        
        return serviceBase + service;
    },
    fbCrawler: function (url,callback,token){
        //var _this = this;
        FB.api(
            '/',
            'POST',
            {"id":url,"scrape":"true",access_token:token},
            function(response) {
                callback(response);
            }
        );
        //FB.api('/'+url,'post', function(response){});
    },
    JSONFormatter: function (data){
        var s = '<ul>';
        for (var prop in data) {
            if (!data.hasOwnProperty(prop)) {
                //The current property is not a direct property of p
                continue;
            }
            //Do your logic with the property here
            s+= '<li>';
            s+= '<span class="json-item-label">' + prop + ':</span>'
            if (typeof data[prop] === 'object'){
                s+= this.JSONFormatter(data[prop]);
            }else{
                s+= ' <span class="json-item-value">' + data[prop] + '</span>';
            }
            s+= '</li>';
        }
        s+= '</ul>';
        
        return s;
    },
    _getBaseUrl: function(){
        var loc = window.location;
        var base_url = loc.protocol + '//'+loc.host;
        
        return base_url;
    },
    _getServiceBaseUrl: function (){
        var baseUrl = this._getBaseUrl() + '/service/';
        
        return baseUrl;
    }
    
};

function facebookShare(articleId, url){
    SocialMedia.fbShare(articleId, url);
}
function twitterShare(url,text){
    SocialMedia.twShare(url,text);
}

function facebookLogin(){
    SocialMedia.fbGetLoginStatus();
}

SocialMedia.init();