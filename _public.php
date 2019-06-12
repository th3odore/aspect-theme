<?php

namespace themes\aspect;

if (!defined('DC_RC_PATH')) { return; }

\l10n::set(dirname(__FILE__).'/locales/'.$_lang.'/public');

$core->tpl->addValue('AspectConfig',array(__NAMESPACE__.'\tplAspectTheme','AspectConfig'));
$core->tpl->addBlock('EntryIfContentIsCut',array(__NAMESPACE__.'\tplAspectTheme','EntryIfContentIsCut'));
$core->tpl->addValue('AspectPostScripts',array(__NAMESPACE__.'\tplAspectTheme','AspectPostScripts'));

class tplAspectTheme
{
    public static function AspectConfig($attr)
    {
        /*
         * THEME CONFIGURATION SECTION
         */

        // Put the URI of the logo you want to add to the header of your blog inside ''
        $logo  = '';

        // Choose between the two styles
        $style = 'default'; // accepts 'default' or 'roman'

        // Show or hide the default copyright message in the footer
        $copyright = 'hide'; // accepts 'show' or 'hide'

        /*
         * END OF THEME CONFIGURATION SECTION
         */

        global $core;

        if ($attr['option'] == 'logo') {
            if ($logo != '') {
                $blogurl = $core->blog->url;
                return '<div id="site-logo"><a href="<?php echo "'.$blogurl.'"; ?>" itemprop="url"><img src="<?php echo "'.$logo.'"; ?>" itemprop="logo" alt="Logo"></a></div>';
            }
        }

        if ($attr['option'] == 'style') {
            if ($style == 'roman') {
                return '
                    <style>
                        .post-content > p {
                            margin: 0;
                            text-indent: 1.5em;
                        }
                        .post-content p iframe {
                            margin-left: -1.5em;
                        }
                        .comment-content p {
                            margin: 0;
                        }
                    </style>';
            }
        }
        if ($attr['option'] == 'copyright') {
            if ($copyright == 'show') {
                $blogname = $core->blog->name;
                return '<div class="footer-div" id="copyright"><em>'.$blogname.'</em> '.__('is powered by <a href="https://dotclear.org/" target="_blank">Dotclear</a> and <a href="http://themes.dotaddict.org/galerie-dc2/details/aspect" target="_blank">Aspect</a>').'</div>';
            }
        }
    }

    // From Ductile theme (http://ductile.dotaddict.org)
    public static function EntryIfContentIsCut($attr,$content)
    {
        global $core;

        if (empty($attr['cut_string']) || !empty($attr['full'])) {
            return '';
        }

        $urls = '0';
        if (!empty($attr['absolute_urls'])) {
            $urls = '1';
        }

        $short = $core->tpl->getFilters($attr);
        $cut = $attr['cut_string'];
        $attr['cut_string'] = 0;
        $full = $core->tpl->getFilters($attr);
        $attr['cut_string'] = $cut;

        return '<?php if (strlen('.sprintf($full,'$_ctx->posts->getContent('.$urls.')').') > '.
            'strlen('.sprintf($short,'$_ctx->posts->getContent('.$urls.')').')) : ?>'.
            $content.
            '<?php endif; ?>';
    }

    public static function AspectPostScripts()
    {
        global $core;
        $qmark = $core->blog->getQmarkURL();
        if ($core->url->type == 'post' || $core->url->type == 'pages') {
            return '<script type="text/javascript">var post_remember_str = \''.__('Remember me').'\'</script>'."\n".'<script type="text/javascript" src="'.$qmark.'pf=post.js"></script>';
        }
    }
}