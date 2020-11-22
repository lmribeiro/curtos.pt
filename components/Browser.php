<?php

namespace app\components;

class Browser
{

    public function getBrowserName($userAgent)
    {
        // Make case insensitive.
        $agent = strtolower($userAgent);

        // If the string *starts* with the string, strpos returns 0 (i.e., FALSE). Do a ghetto hack and start with a space.
        // "[strpos()] may return Boolean FALSE, but may also return a non-Boolean value which evaluates to FALSE."
        //     http://php.net/manual/en/function.strpos.php
        $agent = " " . $agent;

        // Humans / Regular Users     
        if (strpos($agent, 'opera') || strpos($agent, 'opr/')) return 'Opera';
        else if (strpos($agent, 'edge')) return 'Edge';
        else if (strpos($agent, 'chrome')) return 'Chrome';
        else if (strpos($agent, 'safari')) return 'Safari';
        else if (strpos($agent, 'firefox')) return 'Firefox';
        else if (strpos($agent, 'msie') || strpos($agent, 'trident/7')) return 'Internet Explorer';

        // Search Engines 
        else if (strpos($agent, 'google')) return '[Bot] Googlebot';
        else if (strpos($agent, 'bing')) return '[Bot] Bingbot';
        else if (strpos($agent, 'slurp')) return '[Bot] Yahoo! Slurp';
        else if (strpos($agent, 'duckduckgo')) return '[Bot] DuckDuckBot';
        else if (strpos($agent, 'baidu')) return '[Bot] Baidu';
        else if (strpos($agent, 'yandex')) return '[Bot] Yandex';
        else if (strpos($agent, 'sogou')) return '[Bot] Sogou';
        else if (strpos($agent, 'exabot')) return '[Bot] Exabot';
        else if (strpos($agent, 'msn')) return '[Bot] MSN';

        // Common Tools and Bots
        else if (strpos($agent, 'mj12bot')) return '[Bot] Majestic';
        else if (strpos($agent, 'ahrefs')) return '[Bot] Ahrefs';
        else if (strpos($agent, 'semrush')) return '[Bot] SEMRush';
        else if (strpos($agent, 'rogerbot') || strpos($agent, 'dotbot')) return '[Bot] Moz or OpenSiteExplorer';
        else if (strpos($agent, 'frog') || strpos($agent, 'screaming')) return '[Bot] Screaming Frog';

        // Miscellaneous
        else if (strpos($agent, 'facebook')) return '[Bot] Facebook';
        else if (strpos($agent, 'pinterest')) return '[Bot] Pinterest';

        // Check for strings commonly used in bot user agents  
        else if (strpos($agent, 'crawler') || strpos($agent, 'api') ||
            strpos($agent, 'spider') || strpos($agent, 'http') ||
            strpos($agent, 'bot') || strpos($agent, 'archive') ||
            strpos($agent, 'info') || strpos($agent, 'data')) return '[Bot] Other';

        return 'Other (Unknown)';
    }
}
