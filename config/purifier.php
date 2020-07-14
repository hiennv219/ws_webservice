<?php
/**
 * Ok, glad you are here
 * first we get a config instance, and set the settings
 * $config = HTMLPurifier_Config::createDefault();
 * $config->set('Core.Encoding', $this->config->get('purifier.encoding'));
 * $config->set('Cache.SerializerPath', $this->config->get('purifier.cachePath'));
 * if ( ! $this->config->get('purifier.finalize')) {
 *     $config->autoFinalize = false;
 * }
 * $config->loadArray($this->getConfig());
 *
 * You must NOT delete the default settings
 * anything in settings should be compacted with params that needed to instance HTMLPurifier_Config.
 *
 * @link http://htmlpurifier.org/live/configdoc/plain.html
 */

return [
    'encoding'      => 'UTF-8',
    'finalize'      => true,
    'cachePath'     => storage_path('app/purifier'),
    'cacheFileMode' => 0755,
    'settings'      => [
        'default' => [
            'HTML.Doctype'             => 'XHTML 1.0 Strict',
            'HTML.Allowed'             => 'div,b,strong,i,em,a[href|title],ul,ol,li,p[style],br,span[style],img[width|height|alt|src]',
            'CSS.AllowedProperties'    => 'font,font-size,font-weight,font-style,font-family,text-decoration,padding-left,color,background-color,text-align',
            'AutoFormat.AutoParagraph' => true,
            'AutoFormat.RemoveEmpty'   => true,
        ],
        'test'    => [
            'Attr.EnableID' => true
        ],
        "youtube" => [
            "HTML.SafeIframe"      => 'true',
            "URI.SafeIframeRegexp" => "%^(http://|https://|//)(www.youtube.com/embed/|player.vimeo.com/video/)%",
        ],
        'kses' => [
            'HTML.Allowed' => '
                address,a[href|rel|rev|name|target],abbr,acronym,area[alt|coords|href|nohref|shape|target],
                article[align],aside[align],audio[autoplay|controls|loop|muted|preload|src],b,big,
                blockquote[cite],br,button[disabled|name|type|value],caption[align],cite,code,
                col[align|char|charoff|span|valign|width],colgroup[align|char|charoff|span|valign|width],
                del[datetime],dd,dfn,details[align|open],div[align],dl,dt,em,fieldset,figure[align],
                figcaption[align],font[color|face|size],footer[align],
                form[action|accept|accept-charset|enctype|method|name|target],
                h1[align],h2[align],h3[align],h4[align],h5[align],h6[align],header[align],hgroup[align],
                hr[align|noshade|size|width],i,img[alt|align|border|height|hspace|longdesc|vspace|src|usemap|width],
                ins[datetime|cite],kbd,label[for],legend[align],li[align|value],map[name],mark,menu[type],nav[align],
                p[align],pre[width],q[cite],s,samp,span[align],section[align],small,strike,strong,sub,summary[align],sup,
                table[align|bgcolor|border|cellpadding|cellspacing|rules|summary|width],tbody[align|char|charoff|valign],
                td[abbr|align|axis|bgcolor|char|charoff|colspan|headers|height|nowrap|rowspan|scope|valign|width],
                textarea[cols|rows|disabled|name|readonly],tfoot[align|char|charoff|valign],
                th[abbr|align|axis|bgcolor|char|charoff|colspan|headers|height|nowrap|rowspan|scope|valign|width],
                thead[align|char|charoff|valign],title,tr[align|bgcolor|char|charoff|valign],track[default|kind|label|src|srclang],tt,
                u,ul[type],ol[start|type],var,video[autoplay|controls|height|loop|muted|poster|preload|src|width],
                *[style],*[id],*[class],*[dir],*[lang],*[xml:lang],*[title]',
            'CSS.AllowedProperties' => 'text-align,margin,color,float,
                border,background,background-color,border-bottom,border-bottom-color,
                border-bottom-style,border-bottom-width,border-collapse,border-color,border-left,
                border-left-color,border-left-style,border-left-width,border-right,border-right-color,
                border-right-style,border-right-width,border-spacing,border-style,border-top,
                border-top-color,border-top-style,border-top-width,border-width,caption-side,
                clear,cursor,direction,font,font-family,font-size,font-style,
                font-variant,font-weight,height,letter-spacing,line-height,margin-bottom,
                margin-left,margin-right,margin-top,overflow,padding,padding-bottom,
                padding-left,padding-right,padding-top,text-decoration,text-indent,vertical-align,width',
        ],
        'typical' => [ // + iframe + style
            'Attr.EnableID' => true,
            'HTML.Allowed' => 'address,a[href|rel|rev|name|target],abbr,acronym,b,big,blockquote[cite],br,caption[align],
                cite,code,col[align|charoff|span|valign|width],colgroup[align|charoff|span|valign|width],del,dd,dfn,
                div[align],dl,dt,em,font[color|face|size],h1[align],h2[align],h3[align],h4[align],h5[align],h6[align],
                hr[align|noshade|size|width],i,iframe[width|height|src],img[alt|align|border|height|hspace|longdesc|vspace|src|width],
                ins[cite],kbd,li[value],menu,p[align],pre[width],q[cite],s,samp,span,small,strike,strong,sub,sup,
                table[align|bgcolor|border|cellpadding|cellspacing|rules|summary|width],tbody[align|charoff|valign],
                td[abbr|align|bgcolor|charoff|colspan|height|nowrap|rowspan|scope|valign|width],tfoot[align|charoff|valign],
                th[abbr|align|bgcolor|charoff|colspan|height|nowrap|rowspan|scope|valign|width],thead[align|charoff|valign],
                tr[align|bgcolor|charoff|valign],tt,u,ul[type],ol[start|type],var,
                *[style],*[id],*[class],*[dir],*[lang],*[xml:lang],*[title]',
            'CSS.AllowedProperties' => 'text-align,margin,color,float,' .
                'border,background,background-color,border-bottom,border-bottom-color,' .
                'border-bottom-style,border-bottom-width,border-collapse,border-color,border-left,' .
                'border-left-color,border-left-style,border-left-width,border-right,border-right-color,' .
                'border-right-style,border-right-width,border-spacing,border-style,border-top,' .
                'border-top-color,border-top-style,border-top-width,border-width,caption-side,' .
                'clear,font,font-family,font-size,font-style,' .
                'font-variant,font-weight,height,letter-spacing,line-height,margin-bottom,' .
                'margin-left,margin-right,margin-top,padding,padding-bottom,' .
                'padding-left,padding-right,padding-top,text-decoration,text-indent,vertical-align,width',
            'AutoFormat.AutoParagraph' => true,
            'AutoFormat.RemoveEmpty' => true,
            'HTML.SafeIframe' => true,
            'URI.SafeIframeRegexp' => '%^(http://|https://|//)(www.youtube.com/embed/|player.vimeo.com/video/|www.dailymotion.com/embed/video/)%',
        ],
        'page' => [ // + iframe + style
            'Attr.EnableID' => true,
            'HTML.Allowed' => 'address,a[href|rel|rev|name|target],abbr,acronym,b,big,blockquote[cite],br,caption[align],
                cite,code,col[align|charoff|span|valign|width],colgroup[align|charoff|span|valign|width],del,dd,dfn,
                div[align],dl,dt,em,font[color|face|size],h1[align],h2[align],h3[align],h4[align],h5[align],h6[align],
                hr[align|noshade|size|width],i,iframe[width|height|src],img[alt|align|border|height|hspace|longdesc|vspace|src|width],
                ins[cite],kbd,li[value],menu,p[align],pre[width],q[cite],s,samp,span,small,strike,strong,sub,sup,
                table[align|bgcolor|border|cellpadding|cellspacing|rules|summary|width],tbody[align|charoff|valign],
                td[abbr|align|bgcolor|charoff|colspan|height|nowrap|rowspan|scope|valign|width],tfoot[align|charoff|valign],
                th[abbr|align|bgcolor|charoff|colspan|height|nowrap|rowspan|scope|valign|width],thead[align|charoff|valign],
                tr[align|bgcolor|charoff|valign],tt,u,ul[type],ol[start|type],var,
                *[style],*[id],*[class],*[dir],*[lang],*[xml:lang],*[title]',
            'CSS.AllowedProperties' => 'text-align,margin,color,float,' .
                'border,background,background-color,border-bottom,border-bottom-color,' .
                'border-bottom-style,border-bottom-width,border-collapse,border-color,border-left,' .
                'border-left-color,border-left-style,border-left-width,border-right,border-right-color,' .
                'border-right-style,border-right-width,border-spacing,border-style,border-top,' .
                'border-top-color,border-top-style,border-top-width,border-width,caption-side,' .
                'clear,font,font-family,font-size,font-style,' .
                'font-variant,font-weight,height,letter-spacing,line-height,margin-bottom,' .
                'margin-left,margin-right,margin-top,padding,padding-bottom,' .
                'padding-left,padding-right,padding-top,text-decoration,text-indent,vertical-align,width',
            'AutoFormat.AutoParagraph' => true,
            'AutoFormat.RemoveEmpty' => true,
            'HTML.SafeIframe' => true,
            'URI.SafeIframeRegexp' => '%^(http://|https://|//)(www.youtube.com/embed/|player.vimeo.com/video/|www.dailymotion.com/embed/video/)%',
        ],
        'blog' => [ // + iframe + style
            'Attr.EnableID' => true,
            'HTML.Allowed' => 'address,a[href|rel|rev|name|target],abbr,acronym,b,big,blockquote[cite],br,caption[align],
                cite,code,col[align|charoff|span|valign|width],colgroup[align|charoff|span|valign|width],del,dd,dfn,
                div[align],dl,dt,em,font[color|face|size],h1[align],h2[align],h3[align],h4[align],h5[align],h6[align],
                hr[align|noshade|size|width],i,iframe[width|height|src|frameborder],
                img[alt|align|border|height|hspace|longdesc|vspace|src|width],
                ins[cite],kbd,li[value],menu,p[align],pre[width],q[cite],s,samp,span,small,strike,strong,sub,sup,
                table[align|bgcolor|border|cellpadding|cellspacing|rules|summary|width],tbody[align|charoff|valign],
                td[abbr|align|bgcolor|charoff|colspan|height|nowrap|rowspan|scope|valign|width],tfoot[align|charoff|valign],
                th[abbr|align|bgcolor|charoff|colspan|height|nowrap|rowspan|scope|valign|width],thead[align|charoff|valign],
                tr[align|bgcolor|charoff|valign],tt,u,ul[type],ol[start|type],var,
                *[style],*[id],*[class],*[dir],*[lang],*[xml:lang],*[title]',
            'CSS.AllowedProperties' => 'text-align,margin,color,float,' .
                'border,background,background-color,border-bottom,border-bottom-color,' .
                'border-bottom-style,border-bottom-width,border-collapse,border-color,border-left,' .
                'border-left-color,border-left-style,border-left-width,border-right,border-right-color,' .
                'border-right-style,border-right-width,border-spacing,border-style,border-top,' .
                'border-top-color,border-top-style,border-top-width,border-width,caption-side,' .
                'clear,font,font-family,font-size,font-style,' .
                'font-variant,font-weight,height,letter-spacing,line-height,margin-bottom,' .
                'margin-left,margin-right,margin-top,padding,padding-bottom,' .
                'padding-left,padding-right,padding-top,text-decoration,text-indent,vertical-align,width',
            'AutoFormat.AutoParagraph' => true,
            'AutoFormat.RemoveEmpty' => true,
            'HTML.SafeIframe' => true,
            'URI.SafeIframeRegexp' => '%^(http://|https://|//)(www.youtube.com/embed/|player.vimeo.com/video/|www.dailymotion.com/embed/video/|www.instagram.com/)%',
        ],
    ],

];
