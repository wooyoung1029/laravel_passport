<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel Passport 实战</title>
        <link href="http://laravelpassport.loc/css/app.css" rel="stylesheet">

        <style>
            .m-t-md{
                margin-top: 30px;
            }
        </style>

    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <div class="content">

                <div class="container m-t-md">
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">
                            <div class="panel panel-default">
                                <div class="panel-heading">Laravel Passport 实战</div>

                                <div class="panel-body">
                                    <form class="form-horizontal" method="POST" action="#">
                                        <div class="form-group">
                                            <label for="username" class="col-md-4 control-label">用户名</label>

                                            <div class="col-md-6">
                                                <input id="username" type="text" class="form-control" name="password" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="password" class="col-md-4 control-label">密码</label>

                                            <div class="col-md-6">
                                                <input id="password" type="password" class="form-control" name="password" required>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-md-8 col-md-offset-4">
                                                <button type="button" id="loginButton" class="btn btn-primary">
                                                    登录
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}"></script>
        <script>
            $('#loginButton').click(function(event){
                event.preventDefault();
                $.ajax({
                    type:'post',
                    url:'/oauth/token',
                    dataType:'json',
                    data:{
                        'grant_type':'authorization_code',
                        'client_id':'2',
                        //JonyGuan:: 注意这个里填写的client_secret是你php artisan passport:install生成的，注意匹配
                        'client_secret':'SiVVEjgciBPKcrMVWniLWeTeG3X1Kwy6ujGeXQCh',
                        'username':$('#username').val(),
                        'password':$('#password').val(),
                        'scope':''
                    },
                    success:function(data){
                        console.log(data);
                        alert(JSON.stringify(data));
                    },
                    error:function(err){
                        console.log(err);
                        alert('statusCode:'+err.status+'\n'+'statusText:'+err.statusText+'\n'+'description:\n'+JSON.stringify(err.responseJSON));
                    }
                });
            });
        </script>
    </body>
</html>
