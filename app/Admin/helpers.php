<?php

if (!function_exists('ignore_pjax_paths')) {
    /**
     * 排除不使用 Pjax 刷新頁面的後台路徑
     *
     * @param  array  $freshPaths
     * @return void
     */
    function ignore_pjax_paths($freshPaths) {
        $prefix = config('admin.route.prefix');

        $freshPathsJson = collect($freshPaths)->map(function ($path) use ($prefix) {
            return "/\/$prefix\/$path/";
        })->implode(',');
        $freshPathsJson = "[$freshPathsJson]";

        Admin::script(<<<EOT
$(document).on('pjax:start', function (e, contents, options) {
    const freshPaths = $freshPathsJson;
    for (let path of freshPaths) {
        if (options.url.search(path) !== -1) {
            location.reload();
            return false;
        }
    }
});
EOT
        );
    }
}
