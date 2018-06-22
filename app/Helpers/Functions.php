<?php
if (!function_exists('mysql_clusters')) {
    /**
     * 生成Mysql集群配置
     *
     * @param string $hosts 集群地址
     * @param int $port 端口
     * @param string $database 数据库
     * @param string $user 用户名
     * @param string $password 密码
     * @param null|array $options 扩展配置
     * @return array
     */
    function mysql_clusters($hosts, $port, $database, $user, $password, $options = null)
    {
        $clusters = [
            'driver' => 'mysql',
            'database' => $database,
            'username' => $user,
            'password' => $password,
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'strict' => false,
        ];
        if (substr_count($hosts, ';') == 1 && substr_count($hosts, ':') == 2) {
            $hostsArr = explode(';', $hosts);
            foreach ($hostsArr as $hostSet) {
                list($action, $host) = explode(':', $hostSet);
                $clusters[$action] = [
                    'host' => $host,
                    'port' => $port
                ];
            }
        } else {
            $clusters['host'] = $hosts;
            $clusters['port'] = $port;
        }

        if ($options && !empty($options)) {
            $clusters = array_merge($clusters, $options);
        }

        return $clusters;
    }
}