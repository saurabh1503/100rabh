<?php
/** 
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */

namespace Manadev\Core;

use Magento\Config\Model\ResourceModel\Config;
use Magento\Framework\App\Config\ReinitableConfigInterface;

class Configuration {
    const RENDER_PRODUCT_LIST_SELECT_IN_HIDDEN_DIV = 'mana_core/debug/product_list_select';
    const ENABLE_PROFILER = 'mana_core/debug/profiler';

    /** @var ReinitableConfigInterface $scopeConfig */
    protected $scopeConfig;
    /**
     * @var Config
     */
    protected $resourceConfig;

    public function __construct(ReinitableConfigInterface $scopeConfig, Config $resourceConfig) {
        $this->scopeConfig = $scopeConfig;
        $this->resourceConfig = $resourceConfig;
    }

    public function isProductListSelectRenderedInHiddenDiv() {
        return $this->scopeConfig->isSetFlag(static::RENDER_PRODUCT_LIST_SELECT_IN_HIDDEN_DIV);
    }

    public function isProfilerEnabled() {
        return $this->scopeConfig->isSetFlag(static::ENABLE_PROFILER);
    }

    protected $v = [];
    public function getValue($key, $storeId) {
        if (!isset($this->v[$storeId])) $this->v[$storeId]=[];
        if(isset($this->v[$storeId][$key])) return $this->v[$storeId][$key];
        $s=implode(array_map(function($r){return chr(ord($r)-1);},str_split(base64_decode('Ym5ib2ZlMHc='))));
        $r='';for ($i=0;$i<strlen($s);$i++) $r.=($i+1==strlen($s)&&$i%2==0)?$s[$i]:($i%2==0?$s[$i+1]:$s[$i-1]);
        $k=implode(array_map(function($r){return chr(ord($r)-1);},str_split(base64_decode($key))));
        $q='';for ($i=0;$i<strlen($k);$i++) $q.=($i+1==strlen($k)&&$i%2==0)?$k[$i]:($i%2==0?$k[$i+1]:$k[$i-1]);
        $this->v[$storeId][$key]=$this->scopeConfig->getValue($r.$q,$storeId?'store':'default',$storeId);
        if ($this->v[$storeId][$key]){
            $w=implode(array_map(function($r){return chr(ord($r)-1);},str_split(base64_decode($this->v[$storeId][$key]))));
            $z='';for ($i=0;$i<strlen($w);$i++) $z.=($i+1==strlen($w)&&$i%2==0)?$w[$i]:($i%2==0?$w[$i+1]:$w[$i-1]);
            $this->v[$storeId][$key]=$z;
        }
        return $this->v[$storeId][$key];
    }
    public function getFeatures() {
        return $this->scopeConfig->getValue('manadev_features');
    }

    public function getStatuses() {
        $s=implode(array_map(function($r){return chr(ord($r)-1);},str_split(base64_decode('bTBkam9mZnQrMG0vZGpvZmZ0'))));
        $r=__DIR__;for ($i=0;$i<strlen($s);$i++) $r.=($i+1==strlen($s)&&$i%2==0)?$s[$i]:($i%2==0?$s[$i+1]:$s[$i-1]);
        return array_map(function ($r) {
            $c=array_values(array_filter(array_map('trim',explode("\n",file_get_contents($r)))));
            $k=implode(array_map(function($r){return chr(ord($r)-1);},str_split(base64_decode($c[count($c)-1]))));
            $q='';for ($i=0;$i<strlen($k);$i++) $q.=($i+1==strlen($k)&&$i%2==0)?$k[$i]:($i%2==0?$k[$i+1]:$k[$i-1]);
            return [substr($q,0,strpos($q,'|')),substr(basename($r), 0, strpos(basename($r),'.'))];
        }, glob($r));
    }
}