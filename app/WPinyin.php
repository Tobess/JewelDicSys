<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class WPinyin extends Model {

    protected $table = 'pinyin';

    public $timestamps = false;

    private static $_dict = [];

    /*
     * 获得拼音音节
     */
    public static function getAllAndCache()
    {
        if (\Cache::has(\App\WRef::CACHE_KEY_PINYIN_IDX)) {
            $pinyins = explode('|', \Cache::get(\App\WRef::CACHE_KEY_PINYIN_IDX));
        } else {
            $pinyins = [];
            foreach (self::all() as $pinyin) {
                $pinyins[] = $pinyin->key;
            }

            \Cache::forever(\App\WRef::CACHE_KEY_PINYIN_IDX, implode('|', $pinyins));
        }

        return $pinyins;
    }

    /**
     * 获得拼音音节表
     */
    public static function getPinyinIndex()
    {
        return array_merge(self::$pinyinIndex);
    }

    /**
     * 贵金属特殊拼音音节
     */
    public static function getMetalPinyinIndex()
    {
        if (!count(self::$_dict)) {
            self::$_dict = self::getDicts();
        }

        return self::$_dict;
    }

    /**
     * 不满足全拼规则词根
     *
     * @var array
     */
    private static $metalPinyinIndex = [
        '9k',
        '14k',
        '18k',
        '22k',
        '999.5',
        '999.99',
        '800',
        '925',
        '990',
        '999',
        '9995',
        '9999',
        '850',
        '900',
        '916',
        '950',
        '990',
        '999',
        '500',
        '950',
        '990',
        '999',

        // 黄金
        'au375',
        'g375',
        '9kj',
        '14kj',
        'au585',
        'g585',
        '18kj',
        'au750',
        'jinau750',
        'g750',
        'g18K',
        '22kj',
        'au916',
        'g916',
        '22k',
        'zj',
        'au990',
        'g990',
        'au995',
        'g995',
        'qzj',
        'au999',
        'g999',
        'au999.5',
        'g999.5',
        'au999.9',
        'g999.9',
        'wcj',

        // ag 银
        // 银925
        'ag925',
        's925',
        // 足银
        'ag990',
        's990',
        'zy',
        // 千足银
        'ag999',
        's999',
        'qzy',
        // 银999.5
        'ag999.5',
        's999.5',
        // 银999.9
        'ag999.9',
        's999.9',

        // 铂金
        // 铂850
        'pt850',
        // 铂900
        'pt900',
        // 铂950
        'pt950',
        // 铂990
        'pt990',
        'zb',
        // 铂999
        'pt999',
        'qzb',
        // 铂999.5
        'pt999.5',

        // 钯金
        'pd500',
        'pd950',
        'pd990',
        'pd999',

        // 其他
        'bixi',

    ];

    /**
     * 生成词根
     */
    public static function generateDict()
    {
        // 生成拼音词根缓存文件
        $wDictList = \DB::table('words')->where('key', '<>', '')->lists('key');
        $wDictCentents = "<?php return ['" . implode("','", $wDictList) . "']; ?>";
        \Storage::put('WDict.php', $wDictCentents);

        return \Storage::exists('WDict.php');
    }

    /**
     * 获得拼音词根
     */
    private static function getDicts()
    {
        if (!\Storage::exists('WDict.php')) {
            self::generateDict();
        }

        $dict = include(storage_path().'/app/'.'WDict.php');

        return is_array($dict) ? $dict : [];
    }

    /**
     * 拼音音节索引
     *
     * @var array
     */
    private static $pinyinIndex = [
        'a',
        'ai',
        'an',
        'ang',
        'ao',
        'ba',
        'bai',
        'ban',
        'bang',
        'bao',
        'bei',
        'ben',
        'beng',
        'bi',
        'bian',
        'biao',
        'bie',
        'bin',
        'bing',
        'bo',
        'bu',
        'bun',
        'ca',
        'cai',
        'can',
        'cang',
        'cao',
        'ce',
        'cen',
        'ceng',
        'cha',
        'chai',
        'chan',
        'chang',
        'chao',
        'che',
        'chen',
        'cheng',
        'chi',
        'chong',
        'chou',
        'chu',
        'chua',
        'chuai',
        'chuan',
        'chuang',
        'chui',
        'chun',
        'chuo',
        'ci',
        'cong',
        'cou',
        'cu',
        'cuan',
        'cui',
        'cun',
        'cuo',
        'da',
        'dai',
        'dan',
        'dang',
        'dao',
        'de',
        'den',
        'dei',
        'deng',
        'di',
        'dia',
        'dian',
        'diao',
        'die',
        'ding',
        'diu',
        'dong',
        'dou',
        'du',
        'duan',
        'dui',
        'dun',
        'duo',
        'e',
        'ei',
        'en',
        'eng',
        'er',
        'fa',
        'fan',
        'fang',
        'fei',
        'fen',
        'feng',
        'fo',
        'fou',
        'fu',
        'ga',
        'gai',
        'gan',
        'gang',
        'gao',
        'ge',
        'gei',
        'gen',
        'geng',
        'gi',
        'gong',
        'gou',
        'gu',
        'gua',
        'guai',
        'guan',
        'guang',
        'gui',
        'gun',
        'guo',
        'ha',
        'hai',
        'han',
        'hang',
        'hao',
        'he',
        'hei',
        'hen',
        'heng',
        'hong',
        'hou',
        'ho',
        'hu',
        'hua',
        'huai',
        'huan',
        'huang',
        'hui',
        'hun',
        'huo',
        'ji',
        'jia',
        'jian',
        'jiang',
        'jiao',
        'jie',
        'jin',
        'jing',
        'jiong',
        'jiu',
        'ju',
        'juan',
        'jue',
        'jun',
        'ka',
        'kai',
        'kan',
        'kang',
        'kao',
        'ke',
        'ken',
        'keng',
        'kei',
        'kong',
        'kou',
        'ku',
        'kua',
        'kuai',
        'kuan',
        'kuang',
        'kui',
        'kun',
        'kuo',
        'la',
        'lai',
        'lan',
        'lang',
        'lao',
        'le',
        'lei',
        'leng',
        'li',
        'lia',
        'lian',
        'liang',
        'liao',
        'lie',
        'lin',
        'ling',
        'liu',
        'lo',
        'long',
        'lou',
        'lu',
        'lv',
        'luan',
        'lue',
        'lve',
        'lun',
        'luo',
        'm',
        'ma',
        'mai',
        'man',
        'mang',
        'mao',
        'me',
        'mei',
        'men',
        'meng',
        'mi',
        'mian',
        'miao',
        'mie',
        'min',
        'ming',
        'miu',
        'mo',
        'mou',
        'mu',
        'na',
        'nai',
        'nan',
        'nang',
        'nao',
        'ne',
        'nei',
        'nen',
        'neng',
        'ng',
        'ni',
        'nian',
        'niang',
        'niao',
        'nie',
        'nin',
        'ning',
        'niu',
        'nong',
        'nou',
        'nu',
        'nv',
        'nuan',
        'nve',
        'nuo',
        'nun',
        'o',
        'ou',
        'pa',
        'pai',
        'pan',
        'pang',
        'pao',
        'pei',
        'pen',
        'peng',
        'pi',
        'pian',
        'piao',
        'pie',
        'pin',
        'ping',
        'po',
        'pou',
        'pu',
        'qi',
        'qia',
        'qian',
        'qiang',
        'qiao',
        'qie',
        'qin',
        'qing',
        'qiong',
        'qiu',
        'qu',
        'quan',
        'que',
        'qun',
        'ran',
        'rang',
        'rao',
        're',
        'ren',
        'reng',
        'ri',
        'rong',
        'rou',
        'ru',
        'ruan',
        'rui',
        'run',
        'ruo',
        'sa',
        'sai',
        'san',
        'sang',
        'sao',
        'se',
        'sen',
        'seng',
        'sha',
        'shai',
        'shan',
        'shang',
        'shao',
        'she',
        'shei',
        'shen',
        'sheng',
        'shi',
        'shou',
        'shu',
        'shua',
        'shuai',
        'shuan',
        'shuang',
        'shui',
        'shun',
        'shuo',
        'si',
        'song',
        'sou',
        'su',
        'suan',
        'sui',
        'sun',
        'suo',
        'ta',
        'tai',
        'tan',
        'tang',
        'tao',
        'te',
        'teng',
        'ti',
        'tian',
        'tiao',
        'tie',
        'ting',
        'tong',
        'tou',
        'tu',
        'tuan',
        'tui',
        'tun',
        'tuo',
        'uu',
        'wa',
        'wai',
        'wan',
        'wang',
        'wei',
        'wen',
        'weng',
        'wo',
        'wu',
        'xi',
        'xia',
        'xian',
        'xiang',
        'xiao',
        'xie',
        'xin',
        'xing',
        'xiong',
        'xiu',
        'xu',
        'xuan',
        'xue',
        'xun',
        'ya',
        'yan',
        'yang',
        'yao',
        'ye',
        'yi',
        'yin',
        'ying',
        'yo',
        'yong',
        'you',
        'yu',
        'yuan',
        'yue',
        'yun',
        'za',
        'zai',
        'zan',
        'zang',
        'zao',
        'ze',
        'zei',
        'zen',
        'zeng',
        'zha',
        'zhai',
        'zhan',
        'zhang',
        'zhao',
        'zhe',
        'zhei',
        'zhen',
        'zheng',
        'zhi',
        'zhong',
        'zhou',
        'zhu',
        'zhua',
        'zhuai',
        'zhuan',
        'zhuang',
        'zhui',
        'zhun',
        'zhuo',
        'zi',
        'zong',
        'zou',
        'zu',
        'zuan',
        'zui',
        'zun',
        'zuo',
    ];

    public static function match($pinyin)
    {
        $pinyin = trim($pinyin);
        $pLen = strlen($pinyin);

        $cPinyin = self::$pinyinIndex;
        $indexes = [];
        foreach ($cPinyin as $cDic) {
            $offset = 0;
            while ($offset < $pLen && ($pos = stripos($pinyin, $cDic, $offset)) !== false) {
                if (!isset($indexes[$pos]) || !is_array($indexes[$pos]) || !in_array($cDic, $indexes[$pos])) {
                    $indexes[$pos][] = $cDic;
                }
                $offset += strlen($cDic);
            }
        }
        ksort($indexes);
        print_r($indexes);
    }
}
