<?php
/**
 * Created by PhpStorm.
 * User: zhangxiang
 * Date: 2018/10/19
 * Time: 下午1:16
 */

namespace app\index\controller;


use app\model\FriendshipLink;
use think\App;
use think\Controller;
use think\facade\View;

class Base extends Controller
{
    protected $tpl;
    protected $prefix;
    protected $redis_prefix;
    protected $uid;
    public function __construct(App $app = null)
    {
        parent::__construct($app);
        $this->uid = session('xwx_user_id');
        $this->prefix = config('database.prefix');
        $this->redis_prefix = config('cache.prefix')."_";
        $tpl_root = './template/'.config('site.tpl').'/index/';
        $controller = strtolower($this->request->controller());
        $action = strtolower($this->request->action());
        if ($this->request->isMobile()){
            $this->tpl = $tpl_root.$controller.'/'.$action.'.html';
        }else{
            $this->tpl = $tpl_root.$controller.'/'.'pc_'.$action.'.html';
        }
        $links = cache('friendship_link');
        if ($links == false){
            $links = FriendshipLink::all();
            cache('friendship_link',$links,null,'redis');
        }
        View::share([
            'url' => config('site.url'),
            'site_name' => config('site.site_name'),
            'img_site' => config('site.img_site'),
            'links' => $links
        ]);
    }
}