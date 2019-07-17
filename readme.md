# 用户认证与授权:安装并使用 通过 Passport 实现 API 请求认证，请求授权令牌（OAuth2.0认证）
##  安装 
### 首先通过 Composer 包管理器安装 Passport：

    composer require laravel/passport

### 添加配置到app/Kernel.php中的$middlewareGroups中

    \Laravel\Passport\Http\Middleware\CreateFreshApiToken::class,

### 迁移数据库

    php artisan migrate

### 来创建生成安全访问令牌时用到的加密密钥及私人访问和密码访问客户端。生成记录存放在数据表 oauth_clients
    
    php artisan passport:install


### 添加 Laravel\Passport\HasApiTokens trait 到 App\User 模型

    <?php

    namespace App;

    use Laravel\Passport\HasApiTokens;
    use Illuminate\Notifications\Notifiable;
    use Illuminate\Foundation\Auth\User as Authenticatable;

    class User extends Authenticatable
    {
        use HasApiTokens, Notifiable;
    }
    
### 在 AuthServiceProvider 的 boot 方法中调用 Passport::routes 方法，该方法将会为颁发访问令牌、撤销访问令牌、客户端以及私人访问令牌注册必要的路由
    <?php

	namespace App\Providers;

	use Laravel\Passport\Passport;
	use Illuminate\Support\Facades\Gate;
	use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

	class AuthServiceProvider extends ServiceProvider
	{
	    /**
	     * The policy mappings for the application.
	     *
	     * @var array
	     */
	    protected $policies = [
		'App\Model' => 'App\Policies\ModelPolicy',
	    ];

	    /**
	     * Register any authentication / authorization services.
	     *
	     * @return void
	     */
	    public function boot()
	    {
		$this->registerPolicies();

		Passport::routes();

		Passport::tokensExpireIn(now()->addDays(15));

		Passport::refreshTokensExpireIn(now()->addDays(30));
	    }
	}

### 配置文件 config/auth.php修改

    'guards' => [
	    'web' => [
		'driver' => 'session',
		'provider' => 'users',
	    ],

	    'api' => [
		'driver' => 'passport',
		'provider' => 'users',
	    ],
     ],

### 配置完成，创建用户
    
    php artisan make:seeder UsersTableSeeder

### 修改database/seeds/UserTableSeeder.php
    
    <?php
	use Illuminate\Database\Seeder;
	class UsersTableSeeder extends Seeder
	{
	    /**
	     * Run the database seeds.
	     *
	     * @return void
	     */
	    public function run()
	    {
	        DB::table('users')->insert([
				'name'=>'admin',
				'email'=>'admin@qq.com',
				'password'=>bcrypt('admin')
			]);
	    }
	}

### 执行下面命令，来往 user 表中填充刚刚添加好的 seed 数据。

	php artisan db:seed --class=UsersTableSeeder

### 页面和User模型参照代码

### 访问http://dev.passport.test/api/redirect获取code，用postman请求
POST http://dev.passport.test/oauth/token
{
    'grant_type: 'authorization_code', 
    'client_id: '3', 
    'client_secret: 'wnfeb5fU7JkwQC4daIZ1HFQYSPAKbfwcnIEKLQBF', 
    'redirect_uri: 'http://dev.passport.test/auth/callback', 
    'code': 'def50200208dec86a844bc948d451056eefaf7e5ade17081ead0f90b645b68b584473091952df46030b8652883a9b8e0c53f41bf8fdbe24bbcf26c5c66aa39e6df1eed4755c1af21cc72df4ef8c3c5b728ca96452467c9f22bdf02bc5a3cddea90196f12ca97f6112fad2ef204142e1a99c013a689208475b10a83e611e0426bff54eeabcf1dcc856cc5976464428ac2971dd3d5fe4ab6a64a6ec7eaf06bd5ff5f028001a500dfb8629e71e2d0e559098be11d7080d1751c4284ba7db0fb6592bd67a73e8b374e2892d195abe209b8af79cb3098ea931dece03e88d4f5a3b102536168d23a7141c2bffe5992e567a31c4388de83d7fa364e14acd49d29facf19715ee792fe07f6bc4a4895e5c7ce43944cf7963f8faa4c78e8148d0a398f8d2205250f63633ebf1e63b0923535a6055e4f29bbebe8b91db169b35068d78dd1d5d55fc38e95978afd85dbbaf54351b55e1b091a5df191ace79e78eb529ec0c41ef214f2905e91b0e746'
}

结果返回
{
    "token_type": "Bearer",
    "expires_in": 1296000,
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImFkYWVlMWQ5ODFjYWQ2YWVmMTAxYzM4ZDlkMjc4ZmEyOWRhYjE0OGIzZmRmN2FhMjFhYmViZDhiNTFmMWQ4N2I0Mjg4NTU0OTI1OTBiOGIwIn0.eyJhdWQiOiIzIiwianRpIjoiYWRhZWUxZDk4MWNhZDZhZWYxMDFjMzhkOWQyNzhmYTI5ZGFiMTQ4YjNmZGY3YWEyMWFiZWJkOGI1MWYxZDg3YjQyODg1NTQ5MjU5MGI4YjAiLCJpYXQiOjE1NjMzNDM0NDMsIm5iZiI6MTU2MzM0MzQ0MywiZXhwIjoxNTY0NjM5NDQzLCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.kQqDo3LUDqaatbVTEIeUqO-Lk5yKpx5oGUMCrs4dKm4ECIuNRnfWc-1ZIqBwRc4UlewL8RrjZQ9DWdozsDvxErtoa1gjBnluFjqfM_X27P8nIRDqVwEJg1UrN_WW1qepkifX2_62OlLDYKssd3NR9QiO3ihuT0G6KDW4Y1vKv2qVI1Pllj98OTHGpZUMepsujmZ7aPJZCUW3P6KNsdyFJLUPiniFW7PWSyMNtDVoAii7_EqJwAf8Bzzz-x3URWduydEKe3K_jTjpjg4gSj233BAV-SiGqKB4cR2hS_R7fmmsdeiWeDvQsuexNSojRuD9Mv1bdyED9DKAzKTXLKWGT-7kjlbdzKb7g1RjCP9z9yCN6x_U-e3dcIzFltGDUpcBkKpH0phBiwu8R9NVb13xWpPCvInzftPJWC27F-tJVWX7yo4Xu-lgZax887xqHTKtG7sRJ5A-02tAt5HCoevhRDzcteacumZJsx6DoXIl81Pa1dPMURAoqchxpWSx-Bh8UWN3ljz2xjLvlQ1z4P-s7_E9u8ZODZyhHfawLegjYPjm83LoGXlPXX5GJntwiBbFeftBhoHGWYAjQqwGC-bSM3wm8hueEiwP56TM1HBMhIo1Sn-FUFZvzN-L50wTg9RnAMi90Y_helaQ1KwGergYaCCqDgZPHfGWJxvzObXD7Ko",
    "refresh_token": "def50200cc9035a892c1ca99c3bfeef4dcda3740293bd613a92feb2fb6f29e39ac842a75a523c0284ec9fa215449b5c517f64a09610f65bb7353da600c51d50ae20293b627ad57c63e4ab78baf8f16d28ce5ae701bc064d67fa8b6a820e2d76f3eb2e391f31a89a541f508281ebcb9af5bd7c4d033ad2856b6282bdfd01b142949badc4bdbd2731ddf3115dc597a31d0fbd37cf1b94590b307a2ac922c83543523a688b9d7d5121c9ec3a45c71078a5f11d3d586b76fd66329d90e772be3d04fd424af4ea620da6988dba381d16baabd5d746cc415d5e351473e0bb22c4c3d15f09f52f8fc47d42eefc5c3b5ef055800200b3a2d8d00ea7f075bee76dda60aca39723f35a022edfdca64e50275a1e7bdbe96e743df0ddea4a2aa8d14de77c9fd8839ffef9415727f1404345849c53b76e79078837b712f2b8df4b107a6a5431533e01beb16fc3ef3cd107f789787d25834c69d085299eff6e7fd6acd976e17cb2a"
}

### 获取用户信息 访问 http://dev.passport.test/api/user
GET http://dev.passport.test/api/user
header中添加 Authorization -> Type (Bearen Token) -> Token（上面的access_token）

结果返回
{
    "id": 1,
    "name": "admin",
    "email": "admin@qq.com",
    "created_at": null,
    "updated_at": null
}

## 遇到的坑

  确保table oauth_clients中的personal_access_client和password_client字段均为0 否则会报{"error":"invalid_client","error_description":"Client authentication failed","message":"Client authentication failed"}

## 文档
  https://laravelacademy.org/post/9752.html
  https://laravelacademy.org/post/9748.html#toc_2
  