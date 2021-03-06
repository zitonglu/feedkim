<?php 
	/**
	 * 解析feed基础列表页面
	 * 解析$_POST['feedbutton']值
	 * @since 2020-4-17
	 * @param $_POST['feedUrl'] or $_COOKIE['feedKimUrls']
	 * @return array $feedUrls
	 */
	if (isset($_POST['feedbutton'])) {
		$feedUrls = explode(',',$_POST['feedbutton']);
	}elseif(isset($_COOKIE['feedKimUrls'])){
		$feedUrls = explode(',',$_COOKIE['feedKimUrls']);
	}else{
		$feedUrls = array(get_bloginfo('comments_rss2_url'));
	}

	// 删除无效feedUrl
	$feedUrls = array_unique($feedUrls);//删除重复项
	foreach ($feedUrls as $key => $value) {
		if (!feedkim_file_exists($value)) {
			unset($feedUrls[$key]);
		}
	}

	// 为空就跳出
	if(empty($feedUrls)){
		echo '<pre>'.__('数据源信息为空','feedkim').'</pre>';
		return;
	};

	//object,RSS内容集合
	$feed = feedkim_fetch_feed($feedUrls);

	if ($feed->error()) {
		echo '<pre>';
		echo '<p>'.__('RSS源无法读取，请删除','feedkim').'</p>';
		print_r($feed->error());
		echo '</pre>';
	}else{
		$prePage = get_option('posts_per_page');//单页显示文章数
		$paged = ($_GET['feedKimPaged']) ? $_GET['feedKimPaged'] : 0;//当前页数
		$pageCount = count($feed->get_items());//文章总数
		$pagedNum = $prePage * $paged;//文章开始第几篇

		foreach ($feed->get_items($pagedNum,$prePage) as $item){
			$author = ($item->get_author()) ? $item->get_author()->get_name() : null; 
			$timeID = $item->get_date('YmdgiA');//跳窗ID
			//$description = $item->get_description();//获取RSS的des
			$description = $item->get_content();//获取RSS的des
			$number = strlen($description);
			$p = strip_tags($description);
			$html_p = mb_substr($p,0,140);
			$imagesArray = feedkim_get_images($description);//[0]所有图片[1]所有图片地址
			echo '<li class="item">';?>

			<div class="media">
				<div class="media-left">
				    <a target="_blank" rel="nofollow" href="<?php echo $item->get_permalink();?>" title="<?php echo $item->get_title();?>">
				      <img class="media-object" src="//<?php echo feedkim_parse_url($item->get_base());?>/favicon.ico" alt="favicon.ico" onerror="javascript:this.src='<?php bloginfo('template_url'); ?>/image/favicon.ico';">
				    </a>
				</div>
				<div class="media-body">
				    <h4 class="media-heading"><button type="button" class="btn-link btn-h4 text-left" data-toggle="modal" data-target="#<?php echo $timeID;?>"><?php echo $item->get_title();?></button></h4>
				    <h6 class="media-about">
				    <?php 
				    	if($author){
				    		echo '<span class="glyphicon glyphicon-user"></span> '.$author.' ';//作者
				    }?>
				    	<span class="glyphicon glyphicon-dashboard"></span> <?php echo $item->get_date('Y-m-d g:i A');//发布时间?><span class="glyphicon glyphicon-menu-down float-right"></span></h6>
				    <?php
				    if ($number<=180) {
				    	echo '<p>'.$p.'</p>';
				    }else{
				    	//echo $description;
				    ?>
				    <p><?php echo $html_p;?>
					<button type="button" class="btn-link" data-toggle="modal" data-target="#<?php echo $timeID;?>">[...]</button>
					</p>

					<?php }//end description
					if ($imagesArray) {
						if (count($imagesArray[0])==1) {
							echo '<button type="button" class="btn-link" data-toggle="modal" data-target="#'.$timeID.'">'.$imagesArray[0][0].'</button>';
						}else{
							echo '<div class="images-box row">';
							$imagesArray3 = array_slice($imagesArray[1],0,3);
							foreach ($imagesArray3 as $imageUrl) {
								echo '<button type="button" class="btn col-md-4 col-xs-6" data-toggle="modal" data-target="#'.$timeID.'" style="background-image:url('.$imageUrl.'),url('.get_template_directory_uri().'/image/pixels.png)"></button>';
							}
							echo '<span class="clearfix"></span></div>';
						}
					}//end $images
					?>
					<!-- 完整的descriptionn内容 -->
					<div class="modal fade" id="<?php echo $timeID;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
					  <div class="modal-dialog" role="document">
					    <div class="modal-content">
					      <div class="modal-header">
					        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					        <h4 class="modal-title" id="myModalLabel"><?php echo $item->get_title();?></h4>
					      </div>
					      <div class="modal-body">
					        <?php echo $description;?>
					      </div>
					      <div class="modal-footer">
					        <button type="button" class="btn btn-default" data-dismiss="modal"><?php _e('关闭窗口','feedkim');?></button>
					      </div>
					    </div>
					  </div>
					</div><!-- modal end -->
					
				</div>
			</div>
			<?php
			echo "</li>";
		}//end foreach $feed
		//分页，最后用JQ隐藏了，为了无限下拉
		$havepages = $pageCount-$prePage;
		if($havepages>0){
			$PagedAll = ceil($pageCount/$prePage) - 1;
			$PrePagedNum = $paged - 1;//前一页
			$NextPagedNum = $paged + 1;//后一页
		?>
		<li id="pagerNav">
			<nav>
			  <ul class="pager">
	  	
			  	<?php if($PrePagedNum >= 0):?>
			  	<li class="previous">
				  	<a href="<?php bloginfo('url'); ?>/?feedKimPaged=<?php echo $PrePagedNum?>"><span aria-hidden="true">&larr;</span> <?php _e('上一页','feedkim').',';?></a>
			  	</li>
				<?php endif?>

				<?php if($NextPagedNum <= $PagedAll):?>
				<li class="next">
			  		<a href="<?php bloginfo('url'); ?>/?feedKimPaged=<?php echo $NextPagedNum?>"><?php _e('下一页','feedkim').',';?> <span aria-hidden="true">&rarr;</span></a>
			  	</li>
    			<?php endif?>

			  </ul>
			</nav>
		</li>
		<?php }//end pages nav
	}
?>