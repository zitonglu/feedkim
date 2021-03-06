<footer class="footer">
    <p>
        Copyright © 2019-2020 <a href="<?php echo admin_url(); ?>" target="_blank"><?php bloginfo('name'); ?></a> <span class="glyphicon glyphicon-pencil"></span> 
        <a href="//beian.miit.gov.cn/" rel="nofollow" target="_blank"><?php echo get_option( 'feedkim_get_ICP' );?></a> <!--网站备案号-->
        <span class="glyphicon glyphicon-tree-deciduous"></span> <a href="<?php bloginfo('rdf_url'); ?>" target="_blank">RSS</a> 
        Powered By <a href="//cn.wordpress.org" rel="nofollow" target="_blank">WordPress</a>. Theme by <a href="//www.dianzai.cn" target="_blank"><?php _e('电载·中国','feedkim');?></a>
        <?php 
            _e('商业授权版','feedkim');
            if (defined('XMLSF_VERSION')){
                echo ' <a href="'.site_url().'/sitemap.xml" target="_blank" title="xml">SiteMap</a>';
            }
        ?>
    </p>
</footer>

<!-- Bootstrap JS -->
<script src="<?php feedkim_echo_CDN_URL('jquery.min.js')?>"></script>
<script src="<?php feedkim_echo_CDN_URL('bootstrap.min.js')?>"></script>
<!-- 侧栏跟随 JS -->
<script src="<?php feedkim_echo_CDN_URL('theia-sticky-sidebar.js')?>"></script>
<!-- 无限下拉 JS -->
<script src="<?php feedkim_echo_CDN_URL('infinitescroll.min.js')?>"></script>
<!-- 主题（控制侧栏跟随/文章无限下拉/图片加载等）JS -->
<script src="<?php bloginfo('template_url')?>/js/feedkim.min.js"></script>

<script>
    $(document).ready(function () {
        document.cookie = "cookieid=1; expires=60";
        var result = document.cookie.indexOf("cookieid=") != -1;
        if (!result) {
            alert("浏览器未启用cookie,将无法正常浏览本网站,请打开：更多设置——隐私和个人数据——接受cookie，开启");
        }
    });
</script>
<!-- 代码高亮 JS -->
<?php if(is_single() || is_page()):?>
    <script src="<?php bloginfo('template_url')?>/js/prettify.js"></script>
    <script>
        $(function(){
            $("pre").addClass("prettyprint");
            prettyPrint();
        });
    </script>
<?php endif?>
<?php echo get_option('feedkim_bottom_JQ');?>
<?php wp_footer(); ?>
</body>
</html>
<!-- 网页打开时间：<?php timer_stop(1); ?>秒 -->
<!-- 调用数据库次数：<?php echo get_num_queries(); ?>次 -->