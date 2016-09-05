<?php
function tag_search($tag) {
    $find = "";
    if (stripos( $tag,'乳胶')) {$find=$find."49 ";}
	if (stripos( $tag,'束腰')) {$find=$find."46 ";}
	if (stripos($tag,'紧身衣')) {$find=$find."48 ";}
	if (stripos($tag,'憋尿') or stripos($tag,'尿意') or stripos($tag,'漏尿') or stripos($tag,'尿奴') or stripos($tag,'括约肌')) {$find=$find."31 ";}
	return $find;
}
/*
   功能：获取内容
   作者：雷里雷
   日期：2015/7/6
*/

function getcontents($url) {
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.99 Safari/537.36 LBBROWSER');
$str = curl_exec($ch);
if (curl_getinfo($ch, CURLINFO_HTTP_CODE) !== 200) {
$ret = curl_exec($ch);
}
curl_close($ch);
return $str;
}
function mgetcontents($url) {
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (iPhone; CPU iPhone OS 8_0 like Mac OS X) AppleWebKit/600.1.3 (KHTML, like Gecko) Version/8.0 Mobile/12A4345d Safari/600.1.4');
$str = curl_exec($ch);
curl_close($ch);
return $str;
}
/*
   功能：下载函数
   作者：雷里雷
   日期：2015/7/6
*/
function download($url) {
$str = getcontents($url);
$filename = basename($url);
$newf = fopen( $filename , "wb");
fwrite($newf, $str);
fclose($newf);
return true;
}
function load_js($res,$cdn) {
 global $local;
$str = ($local) ?  $res : $cdn;
$str = '<script src="'.$str .'"></script>';
return $str;
}
function load_css($res,$cdn) {
 global $local;
$str = ($local) ?  $res : $cdn;
$str = '<link href="'.$str .'" rel="stylesheet">';
return $str;
}
function get_article($num) {
 global $local;
if($local===true){
	$str=markdown(file_get_contents('article/'.$num.'.md'));
}else{
	$str=markdown(base64_decode(file_get_contents('article_base64/'.$num.'.md')));
}
return $str;
}
   function Markdown($doc) {
        //tab to space
        $doc = str_replace("\t", str_repeat(' ', 4), $doc);
        
        //\r to \n
        $doc = "\n" . str_replace("\r", '', $doc) . "\n";
        
        //remove empty line
        $doc = preg_replace('#\n+#i', "\n", $doc);
        
        //pre code preReplace, just replac to a tag to escape <p> tag replace
        $preCodeTpl     = "\n<preCode %s>";    //< for p skip
        $preCodePattern = '#```([a-z]+)?(.*?)```#is';
        if(preg_match_all($preCodePattern, $doc, $preCodes)) {
            foreach($preCodes[0] as $key => $value) {
                //every pre code into <preCode [index]> tag
                $doc = preg_replace($preCodePattern, sprintf($preCodeTpl, $key), $doc, 1);
            }
        }
        
        //space code preReplace, first one should have 8 space, and the next should only more then 4
        $spaceCodePattern     = '#\n[ ]{8}[^\n]*?(?=\n)(?:\n[ ]{4}[^\n]*?(?=\n))*#is';
        $spaceCodeTpl        = "\n<spaceCode %d>";
        if(preg_match_all($spaceCodePattern, $doc, $spaceCodes)) {
            foreach($spaceCodes[0] as $key => $value) {
                $doc = preg_replace($spaceCodePattern, sprintf($spaceCodeTpl, $key), $doc, 1);
            }
        }
        
        //blockquote preReplace
        $blockquotePattern     = '#(?:\n> \*.*?(?=\n))+#is';
        $blockquoteTpl        = "\n<blockquote %d>";
        if(preg_match_all($blockquotePattern, $doc, $blockquotes)) {
            foreach($blockquotes[0] as $key => $value) {
                $doc = preg_replace($blockquotePattern, sprintf($blockquoteTpl, $key), $doc, 1);
            }
        }
        
        //hr
        $doc = preg_replace('#----*[ ]*(\n)#is', '<hr />\1', $doc);
        
        //h1/h2/h3
		$doc = preg_replace('/(\n)######(.*?)#*(?=\n)/is', '\1<h6>\2</h6>', $doc);
		$doc = preg_replace('/(\n)#####(.*?)#*(?=\n)/is', '\1<h5>\2</h5>', $doc);
		$doc = preg_replace('/(\n)####(.*?)#*(?=\n)/is', '\1<h4>\2</h4>', $doc);
        $doc = preg_replace('/(\n)###(.*?)#*(?=\n)/is', '\1<h3>\2</h3>', $doc);
        $doc = preg_replace('/(\n)##(.*?)#*(?=\n)/is', '\1<h2>\2</h2>', $doc);
        $doc = preg_replace('/(\n)#(.*?)#*(?=\n)/is', '\1<h1>\2</h1>', $doc);
        
        //strong
        $doc = preg_replace('#\*\*([^\n]*?)\*\*#is', '<strong>\1</strong>', $doc);
        
        //em
        $doc = preg_replace('#\*([^\n]*?)\*#is', '<em>\1</em>', $doc);
        
        //code
        $doc = preg_replace('#`(.*?)`#is', '<code>\1</code>', $doc);
        
        //ul li
        $liPattern = '#(?:\n\* [^\n]*?(?=\n))+#is';
        if(preg_match_all($liPattern, $doc, $lis)) {
            foreach($lis[0] as $key => $value) {
                $ul = '<ul>%s</ul>';
                $lis        = preg_replace('#(\n)\* ([^\n]*?)(?=\n)#is', '\1<li>\2</li>', "\n" . $value . "\n");
                $ul         = sprintf($ul, $lis);
                $doc = preg_replace($liPattern, $ul, $doc, 1);
            }
        }
        
        //img
        $doc = preg_replace('#!\[([^\]]*?)\]\(([^\s]*?)(?: "([^"]*?)")?\)#is', '<img src="\2" alt="\1" title="\3" class="img-responsive center-block"/>', $doc);
        
        //a simple
        if(preg_match_all('#\[([^\]]*?)\]\(([^\s]*?)(?:\s"([^"]*?)")?\)#is', $doc, $links)) {
            $linkTpl             = '<a target="_blank" href="%s" title="%s">%s</a>';
            foreach($links[0] as $key => $value) {
                $doc = str_replace($links[0][$key], sprintf($linkTpl, $links[2][$key], $links[3][$key], $links[1][$key]), $doc);
            }
        }
        
        //a normal
        if(preg_match_all('#\[([^\]]*?)\]\[(\d+)\]#is', $doc, $links)) {
            $linkTpl             = '<a target="_blank" href="%s">%s</a>';
            foreach($links[0] as $key => $value) {
                if(preg_match(sprintf('#\n\[%d\]: (.*?)(?=\n)#is', $links[2][$key]), $doc, $linkHref)) {
                    $doc = str_replace($links[0][$key], sprintf($linkTpl, $linkHref[1], $links[1][$key]), $doc);
                }
            }
        }
        //remove all link href
        $doc = preg_replace('#\n\[\d+\]:.*?(?=\n)#is', '', $doc);
        
        //a footnote
        if(preg_match_all('#\[\^(.*?)\]#is', $doc, $footnotes)) {
            $footnoteTpl = '<a href="#fn:%s" id="fnref:%s" title="go to footnote" class="footnote">[%s]</a>';
            $footnoteReplaced = array();
            foreach($footnotes[0] as $key => $value) {
                $footnoteId = $footnotes[1][$key];
                if(isset($footnoteReplaced[$footnoteId])) continue;
                
                $footnoteReplaced[$footnoteId] = true;
                $index            = $key+1;
                $footnoteHash     = sprintf('<span id="fn:%s">[%s] </span>', $footnoteId, $index);
                $footnoteBack     = sprintf('<a class="reversefootnote" title="go back to content" href="#fnref:%s"><-</a><br>', $footnoteId);
                //match footnote by id
                if(preg_match(sprintf('#(\n)\[\^%s\]: (.*?)(?=\n)#is', $footnoteId), $doc, $footnote)) {
                    //footnote link
                    $doc = preg_replace(sprintf('#\[\^%s\]#is', $footnoteId), sprintf($footnoteTpl, $footnoteId, $footnoteId, $index), $doc, 1);
                    //footnote desc
                    $doc = str_replace($footnote[0], '<-fs->' . $footnote[1]. $footnoteHash . $footnote[2] . $footnoteBack . '<-fe->', $doc);
                }
            }
        }
        //put it into footnotes div
        $doc = preg_replace('#<-fs->(.+)<-fe->#is', '<div class="footnotes"><hr><small>\1</small></div>', $doc);
        //remove the unnecessary tags
        $doc = str_replace(array('<-fs->','<-fe->'), '', $doc);
        
        //br
        $doc = preg_replace('#[ ]{4}(?=\n)#is', '<br>', $doc);
        
        //p not before other tag, and not after pre
        $doc = preg_replace('#\n+#i', "\n", $doc);    //remove empty line [important]
        $doc = preg_replace('#(\n)([^<].*?)(?=\n)#i', '\1<p>\2</p>', $doc);
        
        //pre code replace
        if(!empty($preCodes[0])) {
            foreach($preCodes[0] as $key => $value) {
                $preCode    = '<pre class="'.$preCodes[1][$key].'"><ol>%s</ol></pre>';
                $lines      = preg_replace('#(\n)(.*?)(?=\n)#is', '\1<li><code>\2</code></li>', htmlspecialchars($preCodes[2][$key]));
                $preCode    = sprintf($preCode, $lines);
                $doc = str_replace(sprintf($preCodeTpl, $key), $preCode, $doc);
            }
        }
        
        //space code
        if(!empty($spaceCodes[0])) {
            foreach($spaceCodes[0] as $key => $value) {
                $spaceCode    = sprintf('<pre><code>%s%s</code></pre>', htmlspecialchars($value), "\n");
                $doc         = str_replace(sprintf($spaceCodeTpl, $key), $spaceCode, $doc);
            }
        }
        
        //blockquote replace
        if(!empty($blockquotes[0])) {
            foreach($blockquotes[0] as $key => $value) {
                $blockquote = '<blockquote><ul>%s</ul></blockquote>';
                $lis        = preg_replace('#(\n)> \* (.*?)(?=\n)#is', '\1<li>\2</li>', $value . "\n");
                $blockquote = sprintf($blockquote, $lis);
                $doc = str_replace(sprintf($blockquoteTpl, $key), $blockquote, $doc);
            }
        }
        
        return trim($doc, "\n");
    }
    
    function parseHtml($html) {
		
        //\r to \n
        $html = "\n" . str_replace("\r", '', $html) . "\n";
        //h1/h2/h3
        $html = preg_replace('#<h1[^>]*?>(.*?)</h1>#is', '#\1'.str_repeat(' ', 4), $html);
        $html = preg_replace('#<h2[^>]*?>(.*?)</h2>#is', '##\1'.str_repeat(' ', 4), $html);
        $html = preg_replace('#<h3[^>]*?>(.*?)</h3>#is', '###\1'.str_repeat(' ', 4), $html);
        $html = preg_replace('#<h4[^>]*?>(.*?)</h4>#is', '####\1'.str_repeat(' ', 4), $html);
		$html = preg_replace('#<h5[^>]*?>(.*?)</h5>#is', '#####\1'.str_repeat(' ', 4), $html);
		$html = preg_replace('#<h6[^>]*?>(.*?)</h6>#is', '######\1'.str_repeat(' ', 4), $html);
        //hr
        $html = preg_replace('#<hr\s*/?>#is', '---', $html);
        
        //em
        $html = preg_replace('#<em[^>]*?>(.*?)</em>#is', '*\1*', $html);
        
        //pre code
        if(preg_match_all('#<pre><code[^>]*?>(.*?)</code></pre>#is', $html, $tabCodes)) {
            foreach($tabCodes[0] as $key => $value) {
                if(preg_match_all('#.*?\n#is', $tabCodes[1][$key], $lines)) {
                    $space8 = str_repeat(' ', 8);
                    $tabCodes[1][$key] = '';
                    foreach($lines[0] as $k => $v) {
                        $tabCodes[1][$key] .= $space8 . $v;
                    }
                }
                $html = str_replace($tabCodes[0][$key], $tabCodes[1][$key], $html);
            }
        }
        
        //pre ol code
        $liPattern = '#[ ]*<li>(.*?)</li>[ ]*#is';
        $preOlPattern = '#<pre class="([^"]*?)"><ol>(.*?)</ol></pre>#is';
        if(preg_match_all($preOlPattern, $html, $preOls)) {
            foreach($preOls[0] as $key => $value) {
                //li
                if(preg_match_all($liPattern, $preOls[2][$key], $lis)) {
                    foreach($lis[0] as $k => $v) {
                        $index = $k + 1;
                        $html = str_replace($v, strip_tags($lis[1][$k]), $html);
                    }
                }
                $html = preg_replace($preOlPattern, sprintf('```%s\2```', $preOls[1][$key]), $html, 1);
            }
        }
        
        //code
        $html = preg_replace('#<code[^>]*?>(.*?)</code>#is', '`\1`', $html);
        
        //strong
        $html = preg_replace('#<strong[^>]*?>(.*?)</strong>#is', '**\1**', $html);
        
        //img
        $html = preg_replace('#<img class="([^"]*?)" src="([^"]*?)"\s*/?>#is', '![zlata.de](\2)', $html);
        $html = preg_replace('#(!\[.*?\]\(.*?) ""(\))#is', '\1\2', $html);
        
        //a
        $linkPattern = '#<a (?:target="_blank" )?href="([^"]*?)">(.*?)</a>#is';
        if(preg_match_all($linkPattern, $html, $links)) {
            $html .= "\n";
            foreach($links[0] as $key => $value) {
                $index = $key + 1;
                $html = str_replace($value, sprintf('[%s][%d]', $links[2][$key], $index), $html);
                $html .= sprintf("[%d]: %s\n", $index, $links[1][$key]);
            }
        }
        
        //a 注脚
        $footnotePattern = '#<a href="\#fn:([^"]*?)" id="fnref:\1" title="[^"]*?" class="footnote">\[(\d+)\]</a>#is';
        if(preg_match_all($footnotePattern, $html, $footnotes)) {
            foreach($footnotes[0] as $key => $value) {
                $footnote = $footnotes[1][$key];        //code
                $footnoteNum = $footnotes[2][$key];        //1
                $footnoteStr = sprintf('[^%s]', $footnote);
                //part 1
                $html = str_replace($value, $footnoteStr, $html);
                //part 2
                $html = str_ireplace(sprintf('<span id="fn:%s">[%s] </span>', $footnote, $footnoteNum), $footnoteStr . ': ', $html);
                //part 3
                $html = preg_replace(sprintf('#<a href="\#fnref:%s" title="[^"]*?" class="reversefootnote">.*?</a>#is', $footnote), '', $html);
            }
        }
        $html = preg_replace('#<div class="footnotes">\s*---\s*<small>(.*?)</small>\s*</div>#is', '\1', $html);
        $html = preg_replace('#<a class="reversefootnote".*?><-</a><br>#is', '', $html);
        
        //blockquote
        $blockPattern = '#<blockquote[^>]*?>\s*<ul>(.*?)</ul>\s*</blockquote>#is';
        if(preg_match_all($blockPattern, $html, $blocks)) {
            foreach($blocks[0] as $key => $value) {    //Every blockquote
                //li
                if(preg_match_all($liPattern, $blocks[1][$key], $lis)) {
                    foreach($lis[0] as $k => $v) {
                        $html = str_replace($v, '> * ' . $lis[1][$k], $html);
                    }
                }
            }
        }
        
        //All blockquote html
        $html = preg_replace($blockPattern, '\1', $html);
        
        //ul/li
        $ulPattern = '#<ul>(.*?)</ul>#is';
        if(preg_match_all($ulPattern, $html, $uls)) {
            foreach($uls[0] as $key => $value) {    //Every blockquote
                //li
                if(preg_match_all($liPattern, $uls[1][$key], $lis)) {
                    foreach($lis[0] as $k => $v) {
                        $html = str_replace($v, '* ' . $lis[1][$k], $html);
                    }
                }
            }
        }
        //All ul html
        $html = preg_replace($ulPattern, '\1', $html);

        //ol/li
        $olPattern = '#<ol>(.*?)</ol>#is';
        if(preg_match_all($olPattern, $html, $ols)) {
            foreach($ols[0] as $key => $value) {
                //li
                if(preg_match_all($liPattern, $ols[1][$key], $lis)) {
                    foreach($lis[0] as $k => $v) {
                        $index = $k + 1;
                        $html = str_replace($v, sprintf('%d.  %s', $index, $lis[1][$k]), $html);
                    }
                }
            }
        }
        //All ol html
        $html = preg_replace($olPattern, '\1', $html);
        
        //br
        $html = preg_replace('#<br\s*/?>#is', str_repeat(' ', 4), $html);
        
        //p not before pre
		
        $html = preg_replace('#<p[^>]*?>(.*?)</p>#is', '\1'.str_repeat(' ', 4), $html);
		
        $html = strip_tags($html, '<img>');
        return $html;
    }